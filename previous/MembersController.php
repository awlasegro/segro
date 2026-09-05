<?php

namespace App\Http\Controllers;

use App\Models\Funds;
use App\Models\Members;
use App\Models\Membership;
use App\Models\OrderList;
use App\Models\Orders;
use App\Models\ReferenceCodes;
use App\Models\SelectedOrder;
use App\Models\User;
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
                    ->orWhere('username', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('name', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('parent_id', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('phone', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('reference_code', 'LIKE', "%{$searchTerm}%");
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
                ->sum('amount');

            $totalWithdrawals = $user->funds()
                ->where('type', 'withdrawal')
                ->sum('amount');

            $totalCommission = $user->funds()
                ->where('type', 'commission')
                ->sum('amount');

            // Calculate daily commission based on the funds table
            $dailyCommission = $user->funds()
                ->where('type', 'commission')
                ->where('status', 'active')
                ->sum('amount');

            // Get the price of the first incomplete order from the OrderList table
            $firstIncompleteOrderPrice = $user->orders()
                ->where('type', 'Incomplete')
                ->where('status', 'active')
                ->orderBy('created_at', 'asc')
                ->with('orderList') // Ensure the OrderList relationship is loaded
                ->first()?->orderList->price; // Access the price from the related OrderList model

            // Calculate total funds, subtracting the price of the first incomplete order if it exists
            $totalFunds = $totalDeposits + $totalCommission - $totalWithdrawals;

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
                'parent_name' => $user->parent ? $user->parent->username : 'N/A',
            ];
        });

        // Pass the paginated users and their data to the view
        return view('admin.index', ['users' => $userData, 'pagination' => $users]);
    }

    public function dashboard()
    {
        // Today's counts
        $todaysUsers = User::whereDate('created_at', today())->count();
        $todaysOrders = Orders::whereDate('created_at', today())->count();
        $todaysDeposits = Funds::whereDate('created_at', today())
            ->where('type', 'deposit')
            ->sum('amount');
        $todaysWithdrawals = Funds::whereDate('created_at', today())
            ->where('type', 'withdraw')
            ->sum('amount');

        // Retrieve the latest 10 users
        $latestUsers = User::orderBy('created_at', 'desc')->take(10)->get();

        // Pass the data to the view
        return view('admin.dashboard', [
            'todaysUsers' => $todaysUsers,
            'todaysOrders' => $todaysOrders,
            'todaysDeposits' => $todaysDeposits,
            'todaysWithdrawals' => $todaysWithdrawals,
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
        $refCodes = ReferenceCodes::all();
        $memberships = Membership::all();
        $users = User::all();

        return view('admin.add-member', compact('refCodes', 'memberships', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'password' => 'required',
            'vallet_password' => 'required',
            'email' => 'required',
            'parentUser' => 'required',
            'credibility' => 'required',
            'memLevel' => 'required',
            'username' => 'required',
        ]);

        $members = new User();
        $members->username = $request->input('username');
        $members->name = $request->input('name');
        $members->email = $request->input('email');
        $members->phone = $request->input('phone');
        $members->password = Hash::make($request->input('password')); // Hash the password
        $members->vallet_password = Hash::make($request->input('vallet_password')); // Hash the password

        // Generate a 6-digit alphanumeric reference code
        $members->reference_code = strtoupper(Str::random(6));

        $members->membership_level_id = $request->input('memLevel');
        $members->parent_id = $request->input('parentUser');
        $members->credibility = $request->input('credibility');
        $members->min_withdraw = $request->input('min_withdraw');
        $members->max_withdraw = $request->input('max_withdraw');
        $members->user_type = $request->input('userType') ?? 0;
        $members->status = 'active';
        $members->wallet_status = 'deactive';
        $members->remember_token = Str::random(10);
        $members->save();

        $opening_balance = new Funds();
        $opening_balance->user_id = $members->id;
        $opening_balance->amount = $request->input('op_balance');
        $opening_balance->type = 'deposit';
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

        return view('admin.update-user-data', compact('memberships', 'user'));
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
            'username' => 'required',
            'name' => 'required',
            'phone' => 'required',
            'reference_code' => 'required',
            'memLevel' => 'required|exists:memberships,id',
            'parentUser' => 'nullable|exists:users,id',
            'credibility' => 'required',
            'password' => 'nullable', // Optional, must be at least 6 characters if provided
            'wallet_password' => 'nullable', // Optional, must be at least 6 characters if provided
            'min_withdrawal' => 'nullable',
            'max_withdrawal' => 'nullable',
            'user_status' => 'required',
            'wallet_status' => 'required',
            'userType' => 'required',
        ]);

        // Update user data
        $user->username = $request->input('username');
        $user->name = $request->input('name');
        $user->phone = $request->input('phone');
        $user->reference_code = $request->input('reference_code');
        $user->membership_level_id = $request->input('memLevel');
        $user->parent_id = $request->input('parentUser');
        $user->credibility = $request->input('credibility');
        $user->status = $request->input('user_status');
        $user->wallet_status = $request->input('wallet_status');
        $user->user_type = $request->input('userType');

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

        // Retrieve the user
        $user = User::findOrFail($id);

        return view('admin.setup-orders', compact('selected_order_list', 'user'));
    }

    public function update_orders(Request $request, $id)
    {
        // Get all selected orders from the form
        $selected_order_ids = $request->input('selected_orders', []);

        // Delete any unchecked orders from the selected_orders table
        SelectedOrder::where('user_id', $id)
            ->whereNotIn('id', $selected_order_ids)
            ->delete();

        return redirect()->back()->with('order_success', 'Orders updated successfully!');
    }

    public function saveSelectedOrders(Request $request, $user)
    {
        // Validate the request
        $request->validate([
            'selected_orders' => 'required|array|min:1',
            'order_after' => 'required|integer', // Validate the order_after field
        ]);

        // Retrieve the selected orders
        $selectedOrderIds = $request->input('selected_orders');
        $orderAfter = $request->input('order_after');

        // Save selected orders into the selected_orders table
        foreach ($selectedOrderIds as $orderId) {
            SelectedOrder::create([
                'user_id' => $user,
                'order_list_id' => $orderId,
                'order_after' => $orderAfter,
            ]);
        }

        // Redirect back with a success message
        return redirect('administration')->with('order_success', 'Orders have been saved successfully.');
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
            'username' => $request->input('name'),
            'password' => $request->input('password'),
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Check if the user's status is 'active'
            if ($user->status === 'active') {
                // Check if the user is an admin
                if ($user->user_type == 1 || $user->user_type == 2) {
                    return redirect()->intended('administration');
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

    public function userRechargeHistory($user)
    {
        // Fetch the recharge history for the specified user with active status
        $rechargeHistory = Funds::where('user_id', $user)
            ->where('status', 'active')
            ->where('type', 'deposit')
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();
        // Fetch user details
        $userInfo = User::find($user);

        // Pass the recharge history and user ID to the view
        return view('admin.recharge-history', compact('rechargeHistory', 'userInfo'));
    }

    public function userRedemptionHistory($user)
    {
        // Fetch the user's redemption (withdrawal) history with active status
        $redemptionHistory = Funds::where('user_id', $user)
            ->where('status', 'active')
            ->where('type', 'withdrawal')
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();

        // Fetch user details
        $userInfo = User::find($user);

        return view('admin.redemption-history', compact('redemptionHistory', 'userInfo'));
    }
}
