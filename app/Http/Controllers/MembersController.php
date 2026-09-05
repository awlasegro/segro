<?php

namespace App\Http\Controllers;

use App\Models\Funds;
use App\Models\Members;
use App\Models\Membership;
use App\Models\OrderList;
use App\Models\OrderSetting;
use App\Models\Orders;
use App\Models\SelectedOrder;
use App\Models\User;
use App\Models\UserVallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MembersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Get the currently logged-in user
        $loggedInUser = auth()->user();

        // Get the search term from the request
        $searchTerm = $request->input('search');

        // Initialize the query to fetch users with related data
        $query = User::with(['membershipLevel', 'funds', 'orders'])->orderBy('id', 'desc');

        // Apply filtering based on user type
        if ($loggedInUser->user_type === 1) {
            // Super admin sees all users
        } elseif ($loggedInUser->user_type === 2) { // Moderator/Editor
            // Filter users where parent_id is either the current user or their descendants
            $descendantIds = User::where('parent_id', $loggedInUser->id)->pluck('id')->toArray();
            $query->where(function ($subquery) use ($loggedInUser, $descendantIds) {
                $subquery->where('parent_id', $loggedInUser->id)
                    ->orWhereIn('parent_id', $descendantIds);
            });
        } else {
            // Normal user (user_type 0) shouldn't reach here (handled by authentication)
            abort(403, 'Unauthorized access');
        }

        // Apply search filtering (unchanged)
        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('id', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('name', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Fetch the users according to the query with pagination
        $users = $query->paginate(10)->withQueryString();

        // Map the paginated users data
        $userData = $users->map(function ($user) {
            // Calculate today's orders and total order value, considering only active orders
            $todayOrders = $user->orders()
                ->where('status', 'active')
                ->get();

            $todayOrderValue = $todayOrders->sum('total_amount');

            // Calculate total funds from the funds table (sum deposits and subtract withdrawals)
            $totalDeposits = $user->funds()
                ->where('type', 'deposit')
                ->whereIn('status', ['active', 'deactive'])
                ->sum('amount');

            $totalWithdrawals = $user->funds()
                ->where('type', 'withdrawal')
                ->whereIn('status', ['active', 'deactive'])
                ->sum('amount');

            $totalCommission = $user->funds()
                ->where('type', 'commission')
                ->whereIn('status', ['active', 'deactive'])
                ->sum('amount');

            // Calculate daily commission based on the funds table
            $dailyCommission = $user->funds()
                ->where('type', 'commission')
                ->where('status', 'active')
                ->sum('amount');

            // Get the item price of the first incomplete order. Prefer the
            // price frozen on the order itself (set when it was generated)
            // over the OrderList catalog price, which may have changed since
            // — using the live catalog price here would understate what's
            // actually committed if the product's price was edited
            // afterward. The threshold is the price alone, not price +
            // commission — commission is what the platform pays the user on
            // completion, not something they need balance to cover.
            $firstIncompleteOrder = $user->orders()
                ->where('type', 'Incomplete')
                ->where('status', 'active')
                ->orderBy('id', 'asc')
                ->with('orderList') // Ensure the OrderList relationship is loaded
                ->first();

            $totalFunds = $totalDeposits + $totalCommission - $totalWithdrawals;

            $firstIncompleteOrderPrice = null;
            if ($firstIncompleteOrder) {
                $orderPrice = $firstIncompleteOrder->price ?? $firstIncompleteOrder->orderList->price;

                // An affordable admin-bulk order is exempt from the balance
                // display (it wasn't just generated live, so deducting it
                // would be misleading) — but an overpriced order is a real
                // constraint blocking the user regardless of who generated
                // it, so it always shows.
                if ($orderPrice > $totalFunds || $firstIncompleteOrder->source !== 'admin') {
                    $firstIncompleteOrderPrice = $orderPrice;
                }
            }

            if ($firstIncompleteOrderPrice) {
                $totalFunds -= $firstIncompleteOrderPrice;
            }

            // Calculate available funds based on user's membership level
            $availableFunds = $user->membershipLevel->order_limit - $user->orders()->where('status', 'active')->sum('total_amount');

            return [
                'user' => $user,
                'membership_level' => $user->membershipLevel,
                'total_order_limit' => $user->membershipLevel->order_limit,
                'processed_orders_count' => $user->orders()->where('status', 'active')->count(),
                'today_order_value' => $todayOrderValue,
                'total_funds' => $totalFunds,
                'daily_commission' => $dailyCommission,
                'available_funds' => $availableFunds,
                'reference_code' => $user->reference_code,
                'parent_name' => $user->parent ? $user->parent->name : 'N/A',
            ];
        });

        // Pass the paginated users and their data to the view
        return view('admin.index', ['users' => $userData, 'pagination' => $users]);
    }

    /**
     * Full profile view for a single member — every detail an admin might
     * need in one place, instead of hopping between the separate wallet /
     * recharge-history / edit screens.
     */
    public function viewMember($id)
    {
        $user = User::with(['membershipLevel', 'parent'])->findOrFail($id);

        // Financials — same formula used everywhere else in the app
        // (deposit + commission - withdrawal, 'active' status only).
        $totalDeposits = $user->funds()->where('type', 'deposit')->where('status', 'active')->sum('amount');
        $totalWithdrawals = $user->funds()->where('type', 'withdrawal')->where('status', 'active')->sum('amount');
        $totalCommission = $user->funds()->where('type', 'commission')->where('status', 'active')->sum('amount');
        $totalFunds = $totalDeposits + $totalCommission - $totalWithdrawals;

        $pendingDeposits = $user->funds()->where('type', 'deposit')->where('status', 'pending')->sum('amount');
        $pendingWithdrawals = $user->funds()->where('type', 'withdrawal')->where('status', 'pending')->sum('amount');

        $fundsHistory = $user->funds()->orderBy('created_at', 'desc')->take(25)->get();

        // Orders
        $ordersCount = $user->orders()->count();
        $completedOrdersCount = $user->orders()->where('type', 'Complete')->count();
        $incompleteOrdersCount = $user->orders()->where('type', 'Incomplete')->count();
        $ordersHistory = $user->orders()->with('orderList')->orderBy('created_at', 'desc')->take(25)->get();

        // Wallet address on file (for withdrawals)
        $wallet = UserVallet::where('user_id', $user->id)->first();

        return view('admin.member-profile', compact(
            'user',
            'totalDeposits',
            'totalWithdrawals',
            'totalCommission',
            'totalFunds',
            'pendingDeposits',
            'pendingWithdrawals',
            'fundsHistory',
            'ordersCount',
            'completedOrdersCount',
            'incompleteOrdersCount',
            'ordersHistory',
            'wallet',
        ));
    }

    public function dashboard()
    {
        // Today's counts
        $todaysUsers = User::whereDate('created_at', today())->count();
        $todaysOrders = Orders::whereDate('created_at', today())->count();
        $todaysDeposits = Funds::whereDate('created_at', today())
            ->where('type', 'deposit')
            ->where('status', 'active')
            ->sum('amount');
        $todaysWithdrawals = Funds::whereDate('created_at', today())
            ->where('type', 'withdrawal')
            ->where('status', 'active')
            ->sum('amount');

        // All-time member counts
        $totalMembers = User::count();
        $activeMembers = User::where('status', 'active')->count();
        $deactiveMembers = User::where('status', 'deactive')->count();

        // All-time order counts
        $totalOrders = Orders::count();
        $completedOrders = Orders::where('type', 'Complete')->count();
        $pendingOrders = Orders::where('type', 'Incomplete')->count();

        // All-time ledger totals — 'active' status only, matching the balance
        // formula used everywhere else in the app (deposit + commission - withdrawal).
        $totalDeposits = Funds::where('type', 'deposit')->where('status', 'active')->sum('amount');
        $totalWithdrawals = Funds::where('type', 'withdrawal')->where('status', 'active')->sum('amount');
        $totalCommission = Funds::where('type', 'commission')->where('status', 'active')->sum('amount');

        // Requests still awaiting admin approval/rejection
        $pendingDeposits = Funds::where('type', 'deposit')->where('status', 'pending')->count();
        $pendingWithdrawals = Funds::where('type', 'withdrawal')->where('status', 'pending')->count();

        // Membership level breakdown
        $membershipBreakdown = Membership::withCount('users')->orderBy('id')->get();

        // Retrieve the latest 10 users
        $latestUsers = User::orderBy('created_at', 'desc')->take(10)->get();

        // Pass the data to the view
        return view('admin.dashboard', [
            'todaysUsers' => $todaysUsers,
            'todaysOrders' => $todaysOrders,
            'todaysDeposits' => $todaysDeposits,
            'todaysWithdrawals' => $todaysWithdrawals,
            'totalMembers' => $totalMembers,
            'activeMembers' => $activeMembers,
            'deactiveMembers' => $deactiveMembers,
            'totalOrders' => $totalOrders,
            'completedOrders' => $completedOrders,
            'pendingOrders' => $pendingOrders,
            'totalDeposits' => $totalDeposits,
            'totalWithdrawals' => $totalWithdrawals,
            'totalCommission' => $totalCommission,
            'pendingDeposits' => $pendingDeposits,
            'pendingWithdrawals' => $pendingWithdrawals,
            'membershipBreakdown' => $membershipBreakdown,
            'latestUsers' => $latestUsers,
        ]);
    }

    public function add_member()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::all();
        $memberships = Membership::all();

        return view('admin.add-member', compact('users', 'memberships'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * Only the fields actually collected on the Add Member form are
     * validated here. Everything else gets a fixed starting value and is
     * meant to be adjusted afterward on the Edit User screen.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users,username|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:255|unique:users,phone',
            'password' => 'required|confirmed',
            'vallet_password' => 'required',
            'parentUser' => 'required|exists:users,id',
            'credibility' => 'required|numeric|min:0|max:100',
            'op_balance' => 'required|numeric|min:0',
            'min_withdraw' => 'required|numeric|min:0',
            'max_withdraw' => 'required|numeric|min:0|gte:min_withdraw',
            'memLevel' => 'required|exists:memberships,id',
            'userType' => 'required|in:0,1,2',
        ]);

        $username = $request->input('username');

        $members = new User();
        $members->name = $username;
        $members->username = $username;
        $members->email = $request->input('email');
        $members->phone = $request->input('phone');
        $members->password = Hash::make($request->input('password'));
        $members->vallet_password = Hash::make($request->input('vallet_password'));
        // NOT NULL with no default on this table — must be set explicitly.
        $members->remember_token = Str::random(10);

        $members->reference_code = strtoupper(Str::random(6));
        $members->parent_id = $request->input('parentUser');
        $members->user_type = $request->input('userType') ?? 0;
        $members->status = 'active';
        $members->wallet_status = 'deactive';

        $members->membership_level_id = $request->input('memLevel');
        $members->credibility = $request->input('credibility');
        $members->min_withdraw = $request->input('min_withdraw');
        $members->max_withdraw = $request->input('max_withdraw');
        $members->save();

        $opening_balance = new Funds();
        $opening_balance->user_id = $members->id;
        $opening_balance->amount = $request->input('op_balance');
        $opening_balance->type = 'deposit';
        $opening_balance->status = 'active';
        $opening_balance->save();

        return redirect('/administration')->with('add_success', 'Your user has been saved successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Members $members)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $memberships = Membership::all();
        $user = User::findOrFail($id);

        // For the administrative parent-account dropdown.
        $users = User::where('id', '!=', $user->id)->get();

        return view('admin.update-user-data', compact('memberships', 'user', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Members $members, $id)
    {
        $user = User::findOrFail($id);

        // Validate incoming data
        $request->validate([
            'username' => 'required|unique:users,name,'.$id.'|max:255',
            'memLevel' => 'required|exists:memberships,id',
            'credibility' => 'required|numeric',
            'password' => 'nullable',
            'wallet_password' => 'nullable',
            'min_withdrawal' => 'required|numeric',
            'max_withdrawal' => 'required|numeric',
            'user_status' => 'required|in:active,deactive',
            'wallet_status' => 'required|in:active,deactive',
            'parentUser' => 'nullable|exists:users,id|not_in:'.$id,
        ]);

        // Update account data. parent_id is retained only for the existing
        // administrative hierarchy; it does not generate commission.
        $user->name = $request->input('username');
        $user->membership_level_id = $request->input('memLevel');
        $user->credibility = $request->input('credibility');
        $user->status = $request->input('user_status');
        $user->wallet_status = $request->input('wallet_status');
        $user->parent_id = $request->input('parentUser') ?: 0;

        // Update passwords if provided
        if ($request->filled('password')) {
            $user->password = bcrypt($request->input('password'));
        }
        if ($request->filled('wallet_password')) {
            $user->vallet_password = bcrypt($request->input('wallet_password'));
        }

        // Update withdrawal limits if provided
        $user->min_withdraw = $request->input('min_withdrawal', $user->min_withdrawal);
        $user->max_withdraw = $request->input('max_withdrawal', $user->max_withdrawal);

        $user->save();

        return redirect('/administration')->with('upd_success', 'User has been updated successfully.');
    }

    public function resetTodaysOrders(Request $request, User $user)
    {
        // Update status of today's orders to 'deactive'
        $user->orders()->update(['status' => 'deactive']);

        // Update status of today's funds to 'deactive'
        $user->funds()->update(['status' => 'deactive']);

        // Calculate and reset daily commission (if needed)
        $commissionRate = $user->membershipLevel->commission / 100;
        $dailyOrderValue = 0; // Assume commission is based on today's orders
        $dailyCommission = $dailyOrderValue * $commissionRate;

        // Return to the previous page with a success message
        return redirect()->back()->with('success', 'Today\'s orders and funds have been reset.');
    }

    public function reset_single_order($id)
    {
        $order_list = OrderList::orderBy('price', 'asc')->get();
        // Assuming you have a way to get the user ID, or pass it via the route
        $user = User::findOrFail($id); // Or get from the route parameter or session if needed

        return view('admin.reset-orders', compact('order_list', 'user'));
    }

    public function reset_orders($id)
    {
        // Retrieve the selected orders for the user based on user ID
        $selected_order_list = SelectedOrder::where('user_id', $id)
                                            ->with('orderList')  // Assuming there's a relation with the Orders table
                                            ->orderBy('order_after', 'asc')
                                            ->get();

        $user = User::findOrFail($id);

        return view('admin.setup-orders', compact('selected_order_list', 'user'));
    }

    public function update_orders(Request $request, $id)
    {
        $selected_order_ids = $request->input('selected_orders', []);

        SelectedOrder::where('user_id', $id)
            ->whereNotIn('id', $selected_order_ids)
            ->delete();

        return redirect()->back()->with('order_success', 'Orders updated successfully!');
    }

    public function saveSelectedOrders(Request $request, $user)
    {
        $request->validate([
            'selected_orders' => 'required|array',
            'order_after' => 'required|integer',
        ]);

        $orderAfter = $request->input('order_after');
        $selectedOrderIds = $request->input('selected_orders');

        SelectedOrder::where('user_id', $user)->delete();

        foreach ($selectedOrderIds as $orderId) {
            SelectedOrder::create([
                'user_id' => $user,
                'order_list_id' => $orderId,
                'order_after' => $orderAfter,
            ]);
        }

        // Redirect back with a success message
        return redirect()->route('reset.orders', ['id' => $user])->with('order_success', 'Orders have been saved successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Members $members)
    {
    }

    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        // Validate the input
        $request->validate([
            'name' => 'required',
            'password' => 'required',
        ]);

        // Attempt to log in using the name and password
        $credentials = [
            'name' => $request->input('name'),
            'password' => $request->input('password'),
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Check if the user's status is 'active'
            if ($user->status === 'active') {
                // Check if the user is an admin
                if ($user->user_type == 1 || $user->user_type == 2) {
                    return redirect()->intended(route('admin.dashboard'));
                }

                Auth::logout();

                return redirect()->route('admin.login')->with('error', 'You do not have admin access.');
            } else {
                Auth::logout();

                return redirect()->route('admin.login')->with('error', 'Your account is not active.');
            }
        }

        return redirect()->route('admin.login')->with('error', 'Login details are not valid.');
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('admin.login');
    }

    /**
     * Generate this user's remaining orders for today in one batch, up to
     * their membership's daily order limit, instead of one at a time.
     */
    public function generateOrders($id)
    {
        $user = User::findOrFail($id);
        $membership = $user->membershipLevel;

        if (!$membership) {
            return redirect()->back()->with('order_error', 'This user has no membership level assigned.');
        }

        if ($user->status !== 'active') {
            return redirect()->back()->with('order_error', 'This user is not active.');
        }

        $hasIncomplete = Orders::where('user_id', $user->id)
            ->where('type', 'Incomplete')
            ->where('status', 'active')
            ->exists();

        if ($hasIncomplete) {
            return redirect()->back()->with('order_error', "This user already has pending orders. Reset today's orders before generating a new batch.");
        }

        $todaysCompletedOrdersCount = Orders::where('user_id', $user->id)
            ->where('type', 'Complete')
            ->where('status', 'active')
            ->count();

        $remaining = $membership->order_limit - $todaysCompletedOrdersCount;

        if ($remaining <= 0) {
            return redirect()->back()->with('order_error', "This user has already reached today's order limit.");
        }

        // Balance snapshot: every generated order in this batch is priced off
        // this same starting balance (it does not change until orders are
        // actually completed by the user).
        $funds = Funds::where('user_id', $user->id)
            ->whereIn('type', ['deposit', 'commission'])
            ->whereIn('status', ['active', 'deactive'])
            ->sum('amount')
            - Funds::where('user_id', $user->id)
                ->where('type', 'withdrawal')
                ->whereIn('status', ['active', 'deactive'])
                ->sum('amount');

        if ($funds < 30) {
            return redirect()->back()->with('order_error', "This user's balance is below 30, orders cannot be generated.");
        }

        $selectedOrders = SelectedOrder::where('user_id', $user->id)->get();
        $created = 0;

        // Admin-configurable commission rate for orders matched to a curated SelectedOrder slot
        $selectedOrderCommissionRate = OrderSetting::current()->selected_order_commission_rate / 100;

        for ($position = $todaysCompletedOrdersCount; $position < $todaysCompletedOrdersCount + $remaining; ++$position) {
            $matched = $selectedOrders->where('order_after', $position);

            if ($matched->isNotEmpty()) {
                foreach ($matched as $selectedOrder) {
                    $orderListIds = explode(',', $selectedOrder->order_list_id);

                    foreach (OrderList::whereIn('id', $orderListIds)->get() as $orderListItem) {
                        $this->createQueuedOrder($user, $orderListItem, $orderListItem->price * $selectedOrderCommissionRate);
                        ++$created;
                    }
                }

                continue;
            }

            $minPrice = 0.3 * $funds;
            $maxPrice = 0.9 * $funds;
            $orderListItem = OrderList::where('status', 'active')
                ->whereBetween('price', [$minPrice, $maxPrice])
                ->inRandomOrder()
                ->first();

            if (!$orderListItem) {
                break;
            }

            $commission = $orderListItem->price * ($membership->commission / 100);
            $this->createQueuedOrder($user, $orderListItem, $commission);
            ++$created;
        }

        if ($created === 0) {
            return redirect()->back()->with('order_error', 'No matching orders were found to generate for this user.');
        }

        return redirect()->route('order.queue', $user->id)->with('order_success', "Generated {$created} orders for {$user->name}.");
    }

    private function createQueuedOrder(User $user, OrderList $orderListItem, $commission)
    {
        $order = new Orders();
        $order->user_id = $user->id;
        $order->order_id = $orderListItem->id;
        $order->type = 'Incomplete';
        $order->status = 'active';
        $order->price = $orderListItem->price;
        $order->commission = $commission;
        $order->total_amount = $orderListItem->price + $commission;
        $order->source = 'admin';
        $order->save();

        return $order;
    }

    /**
     * Show a user's generated order queue for today (pending + completed),
     * with pending orders editable inline.
     */
    public function orderQueue($id)
    {
        $user = User::findOrFail($id);

        $orders = Orders::where('user_id', $user->id)
            ->whereIn('type', ['Incomplete', 'Complete'])
            ->where('status', 'active')
            ->orderBy('id', 'asc')
            ->with('orderList')
            ->get();

        return view('admin.order-queue', compact('user', 'orders'));
    }

    /**
     * Inline-edit a single queued order's price/commission. Only orders that
     * are still pending (Incomplete) may be edited — once an order is
    * completed, its commission has already been paid out into the funds
    * ledger, so editing it afterward would silently desync the order record
    * from money that has already moved.
     */
    public function updateQueuedOrder(Request $request, $id)
    {
        $order = Orders::findOrFail($id);

        if ($order->type !== 'Incomplete') {
            return response()->json([
                'error' => 'Only pending (Incomplete) orders can be edited.',
            ], 422);
        }

        $validated = $request->validate([
            'price' => 'required|numeric',
            'commission' => 'required|numeric',
        ]);

        $order->price = $validated['price'];
        $order->commission = $validated['commission'];
        $order->total_amount = $validated['price'] + $validated['commission'];
        $order->save();

        return response()->json([
            'success' => true,
            'price' => number_format($order->price, 2),
            'commission' => number_format($order->commission, 2),
            'total_amount' => number_format($order->total_amount, 2),
        ]);
    }

    public function user_recharge_history($id)
    {
        // Retrieve the user by ID
        $user = User::findOrFail($id);

        // Get the user's funds history
        $fundsHistory = $user->funds()->orderBy('created_at', 'desc')->get();

        // Pass the funds history to the view
        return view('admin.user-recharge-history', compact('user', 'fundsHistory'));
    }
}
