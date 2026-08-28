<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\OrderList;
use App\Models\SelectedOrder;
use App\Models\Funds;
use App\Http\Requests\StoreOrdersRequest;
use App\Http\Requests\UpdateOrdersRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        // Calculate the available balance for the user
        $funds = Funds::where('user_id', $user->id)
        ->whereIn('type', ['deposit', 'commission'])
        ->whereIn('status', ['active', 'deactive'])
        ->sum('amount')
        - Funds::where('user_id', $user->id)
            ->where('type', 'withdrawal')
            ->whereIn('status', ['active', 'deactive'])
            ->sum('amount');

        // Balance shown on the review screen's "Your balance" row. Moved
        // here verbatim from an inline @php block that used to live in
        // submit-order.blade.php — note it has no ->whereIn('status', ...)
        // filter, unlike $funds above, so it's a different (pre-existing)
        // number, not a typo. Preserved as-is rather than unified with
        // $funds, since the task was to relocate this logic, not change it.
        $modalFunds = Funds::where('user_id', $user->id)
            ->whereIn('type', ['deposit', 'commission'])
            ->sum('amount')
            - Funds::where('user_id', $user->id)
                ->where('type', 'withdrawal')
                ->sum('amount');


        // Get today's completed orders count
        $todaysCompletedOrdersCount = Orders::where('user_id', $user->id)
            ->where('type', 'Complete')
            ->where('status', 'active')
            ->count();


        // Fetch the user's membership details
        $membership = $user->membershipLevel;

        if ($user->status !== 'active') {
            return redirect()->route('data-optimization')
                ->with('account_message', 'Your account is disabled. Please contact support.');
        }
        // Check if today's order count equals the user's order limit
        if ($todaysCompletedOrdersCount >= $membership->order_limit) {
            return redirect()->route('data-optimization')
                ->with('order_message', 'You have completed your order limit for today.');
        }

        // Check if the user's available balance is less than 30
        if ($funds < 30) {
            return redirect()->route('support')
                ->with('blc_message', 'Your balance is less than 30. Please recharge first.');
        }

        // Check for the earliest pending order for the user (oldest first, so a
        // batch of orders generated in advance is worked through in order)
        $lastIncompleteOrder = Orders::where('user_id', $user->id)
            ->where('type', 'Incomplete')
            ->where('status', 'active')
            ->orderBy('id', 'asc')
            ->first();

        if ($lastIncompleteOrder) {
            // A pending order already exists — whether it was just generated
            // on demand or pre-loaded by an admin in a batch — so show it
            // through the same review screen instead of generating a new one.
            $orderListItem = $lastIncompleteOrder->orderList;
            $orderPrice = $lastIncompleteOrder->price ?? $orderListItem->price;
            $commission = $lastIncompleteOrder->commission ?? ($orderPrice * ($membership->commission / 100));
            $totalAmount = $lastIncompleteOrder->total_amount ?? ($orderPrice + $commission);
            // The afford-ability threshold is the order's price alone, not
            // price + commission — commission is what the platform pays the
            // user on completion, not something they need balance to cover.
            $overpricedAmount = max(0, $orderPrice - $funds);

            $orderData = [
                [
                    'order' => $orderListItem,
                    'order_row_id' => $lastIncompleteOrder->id,
                    'price' => $orderPrice,
                    'commission' => $commission,
                    'total_value' => $totalAmount,
                    'image' => $orderListItem->image,
                    'overpriced_amount' => $overpricedAmount,
                ]
            ];

            return view('user.submit-order', ['orderData' => $orderData, 'funds' => $modalFunds]);
        }


        // Fetch all selected orders for the user
        $selectedOrders = SelectedOrder::where('user_id', $user->id)->get();

        $orderData = [];

        foreach ($selectedOrders as $selectedOrder) {
            // Check if today's completed orders count matches the `order_after` value
            if ($todaysCompletedOrdersCount == $selectedOrder->order_after) {
                $selectedOrderIds = explode(',', $selectedOrder->order_list_id);
                $orders = OrderList::whereIn('id', $selectedOrderIds)->get();

                foreach ($orders as $order) {
                    $orderPrice = $order->price;
                    $commissionRate = 0.10; // Fixed 10% commission for selected orders
                    $commission = $orderPrice * $commissionRate;
                    $totalAmount = $orderPrice + $commission;

                    $overpricedAmount = max(0, $orderPrice - $funds);

                    // Save the order to the Orders table
                    $newOrder = new Orders();
                    $newOrder->user_id = $user->id;
                    $newOrder->order_id = $order->id;
                    $newOrder->type = 'Incomplete';
                    $newOrder->status = 'active';
                    $newOrder->price = $orderPrice;
                    $newOrder->commission = $commission;
                    $newOrder->total_amount = $totalAmount;
                    $newOrder->source = 'user';
                    $newOrder->save();

                    $orderData[] = [
                        'order' => $order,
                        'order_row_id' => $newOrder->id,
                        'price' => $orderPrice,
                        'commission' => $commission,
                        'total_value' => $totalAmount,
                        'image' => $order->image,
                        'overpriced_amount' => $overpricedAmount,
                    ];
                }
            }
        }

        if (count($orderData) > 0) {
            return view('user.submit-order', ['orderData' => $orderData, 'funds' => $modalFunds]);
        } else {
            // If the conditions do not match, get a random order and save it
            $minPrice = 0.3 * $funds;
            $maxPrice = 0.9 * $funds;
            $order = OrderList::where('status', 'active')
                ->whereBetween('price', [$minPrice, $maxPrice])
                ->inRandomOrder()
                ->first();

            if (!$order) {
                return redirect()->back()->with('error', 'No active orders found.');
            }

            $orderPrice = $order->price;
            $orderImage = $order->image;

            $commissionRate = $membership->commission / 100;
            $commission = $orderPrice * $commissionRate;

            $totalAmount = $orderPrice + $commission;

            // The afford-ability threshold is the order's price alone, not
            // price + commission — commission is what the platform pays the
            // user on completion, not something they need balance to cover.
            $overpricedAmount = max(0, $orderPrice - $funds);

            // Save the order to the Orders table
            $newOrder = new Orders();
            $newOrder->user_id = $user->id;
            $newOrder->order_id = $order->id;
            $newOrder->type = 'Incomplete';
            $newOrder->status = 'active';
            $newOrder->price = $orderPrice;
            $newOrder->commission = $commission;
            $newOrder->total_amount = $totalAmount;
            $newOrder->source = 'user';
            $newOrder->save();

            $orderData = [
                [
                    'order' => $order,
                    'order_row_id' => $newOrder->id,
                    'price' => $orderPrice,
                    'commission' => $commission,
                    'total_value' => $totalAmount,
                    'image' => $orderImage,
                    'overpriced_amount' => $overpricedAmount,
                ]
            ];

            return view('user.submit-order', ['orderData' => $orderData, 'funds' => $modalFunds]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreOrdersRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrdersRequest $request)
    {
        //
    }
    public function support()
    {
        $messages = Auth::user()->chatMessages()->orderBy('id')->get();

        return view('user.support', compact('messages'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Orders  $orders
     * @return \Illuminate\Http\Response
     */
    public function show(Orders $orders)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Orders  $orders
     * @return \Illuminate\Http\Response
     */
    public function edit(Orders $orders)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateOrdersRequest  $request
     * @param  \App\Models\Orders  $orders
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOrdersRequest $request, Orders $orders)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Orders  $orders
     * @return \Illuminate\Http\Response
     */
    public function destroy(Orders $orders)
    {
        //
    }

    public function history()
    {
        $userId = auth()->id();
        $today = now()->format('Y-m-d');

        // Fetch today's orders and their counts
        $todaysCompletedOrdersCount = Orders::where('user_id', $userId)
                                            ->where('type', 'Complete')
                                            ->where('status', 'active')
                                            ->count();

        // Fetch the user's membership details
        $membership = auth()->user()->membershipLevel;

        // Fetch the selected orders for the user, if any
        $selectedOrders = SelectedOrder::where('user_id', $userId)->get();
        $selectedOrderIds = $selectedOrders->pluck('order_list_id')->flatten()->toArray();

        // Fetch the earliest pending order (oldest first, so a batch of orders
        // generated in advance is worked through in order)
        $oneIncompleteOrder = Orders::where('user_id', $userId)
                                    ->where('type', 'Incomplete')
                                    ->where('status', 'active')
                                    ->orderBy('id', 'asc')
                                    ->first();

        $oneIncompleteOrderPrice = null;
        $oneIncompleteOrderCommission = null;

        if ($oneIncompleteOrder) {
            $oneIncompleteOrderPrice = $oneIncompleteOrder->price ?? $oneIncompleteOrder->orderList->price;
            $oneIncompleteOrderCommission = $oneIncompleteOrder->commission ?? (
                $oneIncompleteOrderPrice * (in_array($oneIncompleteOrder->orderList->id, $selectedOrderIds) ? 0.10 : ($membership->commission / 100))
            );
        }

        // Fetch completed orders, ordered by 'id' in descending order
        $completedOrders = Orders::where('user_id', $userId)
                                ->where('type', 'Complete')
                                ->where('status', 'active')
                                ->orderBy('id', 'desc')  // Order by descending 'id'
                                ->get()
                                ->map(function ($order) use ($selectedOrderIds, $membership) {
            $orderPrice = $order->price ?? $order->orderList->price;
            $commission = $order->commission ?? (
                $orderPrice * (in_array($order->orderList->id, $selectedOrderIds) ? 0.10 : ($membership->commission / 100))
            );
            $totalAmount = $orderPrice + $commission;

            return [
                'title' => $order->orderList->title,
                'price' => $orderPrice,
                'commission' => $commission,
                'created_at' => $order->created_at->format('Y-m-d'),
                'totalPrice' => $totalAmount,
                'image' => $order->orderList->image,
                'status' => $order->type,
            ];
        });

        // Fetch on-hold orders
        $onHoldOrders = Orders::where('user_id', $userId)
                            ->where('type', 'Incomplete')
                            ->where('status', 'active')
                            ->get()
                            ->map(function ($order) use ($selectedOrderIds, $membership) {
            $orderPrice = $order->price ?? $order->orderList->price;
            $commission = $order->commission ?? (
                $orderPrice * (in_array($order->orderList->id, $selectedOrderIds) ? 0.10 : ($membership->commission / 100))
            );
            $totalAmount = $orderPrice + $commission;

            return [
                'title' => $order->orderList->title,
                'price' => $orderPrice,
                'commission' => $commission,
                'created_at' => $order->created_at->format('Y-m-d'),
                'totalPrice' => $totalAmount,
                'image' => $order->orderList->image,
                'status' => $order->type,
            ];
        });

        return view('user.history', compact(
            'oneIncompleteOrder',
            'oneIncompleteOrderPrice',
            'oneIncompleteOrderCommission',
            'completedOrders',
            'onHoldOrders',
            'selectedOrderIds',
            'membership'
        ));
    }




    public function processOrder(Request $request)
{
    // Get the currently authenticated user
    $user = Auth::user();

    // Validate request data
    $validated = $request->validate([
        'order_id' => 'required|integer|exists:orders,id',
        'commission' => 'required|numeric',
    ]);

    // Find the order by ID and ensure it belongs to the user
    $order = Orders::where('user_id', $user->id)
                   ->where('id', $request->input('order_id'))
                   ->where('type', 'Incomplete')
                   ->first();

    if ($order) {
        // Update the order status to Complete
        $order->type = 'Complete';
        $order->save();

        // Use the commission stored on the order itself (set when the order was
        // generated) rather than trusting the value submitted from the form.
        $commission = $order->commission ?? $request->input('commission');

        // Create a new fund record for the commission for the current user
        $user->funds()->create([
            'amount' => $commission,
            'type' => 'commission',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('data-optimization')->with('order_success_message', 'Order completed successfully.');
    }

    return redirect()->back()->with('error', 'Order not found or already completed.');
}





}
