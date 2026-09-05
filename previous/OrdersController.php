<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrdersRequest;
use App\Http\Requests\UpdateOrdersRequest;
use App\Models\Funds;
use App\Models\OrderList;
use App\Models\Orders;
use App\Models\SelectedOrder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        // Check if the user's available balance is less than 50
        if ($funds < 50) {
            return redirect()->route('support')
                ->with('blc_message', 'Your balance is less than 50. Please recharge first.');
        }

        // Check for the last incomplete order for the user
        $lastIncompleteOrder = Orders::where('user_id', $user->id)
            ->where('type', 'Incomplete')
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->first();

        if ($lastIncompleteOrder) {
            // If the last incomplete order exists, return a view with a JavaScript alert and redirect
            return view('user.alert-and-redirect', [
                'message' => 'Please complete your current pending order before proceeding.',
                'redirect_url' => route('history'),
            ]);
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
                    $commissionRate = 0.30; // Fixed 10% commission for selected orders
                    $commission = $orderPrice * $commissionRate;
                    $totalAmount = $orderPrice + $commission;

                    $overpricedAmount = max(0, $orderPrice - $funds);

                    // Save the order to the Orders table
                    $newOrder = new Orders();
                    $newOrder->user_id = $user->id;
                    $newOrder->order_id = $order->id;
                    $newOrder->type = 'Incomplete';
                    $newOrder->status = 'active';
                    $newOrder->total_amount = $totalAmount;
                    $newOrder->save();

                    $orderData[] = [
                        'order' => $order,
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
            return view('user.submit-order', ['orderData' => $orderData]);
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

            $overpricedAmount = max(0, $totalAmount - $funds);

            // Save the order to the Orders table
            $newOrder = new Orders();
            $newOrder->user_id = $user->id;
            $newOrder->order_id = $order->id;
            $newOrder->type = 'Incomplete';
            $newOrder->status = 'active';
            $newOrder->total_amount = $totalAmount;
            $newOrder->save();

            $orderData = [
                [
                    'order' => $order,
                    'price' => $orderPrice,
                    'commission' => $commission,
                    'total_value' => $totalAmount,
                    'image' => $orderImage,
                    'overpriced_amount' => $overpricedAmount,
                ],
            ];

            return view('user.submit-order', ['orderData' => $orderData]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    public function submitOrder(Request $request)
    {
        // Get the currently authenticated user
        $user = Auth::user();

        // Find the order by ID and ensure it belongs to the user
        $order = Orders::where('user_id', $user->id)
                    ->where('order_id', $request->input('order_id'))
                    ->where('type', 'Incomplete')
                    ->first();

        if ($order) {
            // Update the order status to Complete
            $order->type = 'Complete';
            $order->save();

            // Get the commission from the form
            $commission = $request->input('commission');

            // Create a new fund record for the commission for the current user
            $user->funds()->create([
                'amount' => $commission,
                'type' => 'commission',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Check if the user has a parent user (parent_id)
            if ($user->parent_id) {
                // Find the parent user
                $parentUser = User::find($user->parent_id);

                if ($parentUser) {
                    // Calculate 30% of the commission for the parent user
                    $parentCommission = $commission * 0.30;

                    // Create a new fund record for the parent user's commission
                    $parentUser->funds()->create([
                        'amount' => $parentCommission,
                        'type' => 'deposit',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            return redirect()->route('data-optimization')->with('order_success_message', 'Order completed successfully.');
        }

        return redirect()->back()->with('error', 'Order not found or already completed.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrdersRequest $request)
    {
    }

    public function support()
    {
        return view('user.support');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Orders $orders)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Orders $orders)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOrdersRequest $request, Orders $orders)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Orders $orders)
    {
    }

    public function history()
    {
        $userId = auth()->id();
        $today = now()->format('Y-m-d');

        // Fetch user's funds (deposit + commission - withdrawal)
        $funds = Funds::where('user_id', $userId)
                    ->whereIn('type', ['deposit', 'commission'])
                    ->sum('amount')
                    - Funds::where('user_id', $userId)
                        ->where('type', 'withdrawal')
                        ->sum('amount');

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

        // Fetch the one incomplete order
        $oneIncompleteOrder = Orders::where('user_id', $userId)
                                    ->where('type', 'Incomplete')
                                    ->where('status', 'active')
                                    ->first();

        // Fetch completed orders, ordered by 'id' in descending order
        $completedOrders = Orders::where('user_id', $userId)
                                ->where('type', 'Complete')
                                ->where('status', 'active')
                                ->orderBy('id', 'desc')  // Order by descending 'id'
                                ->get()
                                ->map(function ($order) use ($selectedOrderIds, $membership) {
                                    $orderPrice = $order->orderList->price;
                                    $commissionRate = in_array($order->orderList->id, $selectedOrderIds) ? 0.30 : ($membership->commission / 100);
                                    $commission = $orderPrice * $commissionRate;
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
                                $orderPrice = $order->orderList->price;
                                $commissionRate = in_array($order->orderList->id, $selectedOrderIds) ? 0.30 : ($membership->commission / 100);
                                $commission = $orderPrice * $commissionRate;
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

        return view('user.history', compact('oneIncompleteOrder', 'completedOrders', 'onHoldOrders', 'funds', 'selectedOrderIds', 'membership'));
    }

    public function processOrder(Request $request)
    {
        // Get the currently authenticated user
        $user = Auth::user();

        // Validate request data
        $validated = $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
            'commission' => 'required|numeric|min:0',
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

            // Get the commission from the form
            $commission = $request->input('commission');

            // Create a new fund record for the commission for the current user
            $user->funds()->create([
                'amount' => $commission,
                'type' => 'commission',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Check if the user has a parent user (parent_id)
            if ($user->parent_id) {
                // Find the parent user
                $parentUser = User::find($user->parent_id);

                if ($parentUser) {
                    // Calculate 30% of the commission for the parent user
                    $parentCommission = $commission * 0.30;

                    // Create a new fund record for the parent user's commission
                    $parentUser->funds()->create([
                        'amount' => $parentCommission,
                        'type' => 'deposit',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            return redirect()->route('data-optimization')->with('order_success_message', 'Order completed successfully.');
        }

        return redirect()->back()->with('error', 'Order not found or already completed.');
    }
}
