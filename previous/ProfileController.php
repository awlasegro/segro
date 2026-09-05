<?php

namespace App\Http\Controllers;

use App\Models\Funds;
use App\Models\Membership;
use App\Models\OrderList;
use App\Models\Orders;
use App\Models\User;
use App\Models\UserVallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function index()
    {
        // Get the currently authenticated user
        $user = Auth::user();

        // Load the user's related data using Eloquent relationships
        $user->load('membershipLevel', 'funds', 'orders');

        // Calculate total funds (sum deposits and subtract withdrawals)
        $totalDeposits = $user->funds()
            ->where('type', 'deposit')
            ->sum('amount');

        $totalWithdrawals = $user->funds()
            ->where('type', 'withdrawal')
            ->sum('amount');

        $totalCommission = $user->funds()
            ->where('type', 'commission')
            ->sum('amount');
        $totalFunds = $totalDeposits + $totalCommission - $totalWithdrawals;

        // Calculate today's orders and total order value, considering only active orders
        $todayOrders = $user->orders()
            ->where('status', 'active')
            ->get();

        // Calculate today's commission
        $todayCommission = $user->funds()
            ->where('type', 'commission')
            ->where('status', 'active')
            ->sum('amount');

        $totalTodayOrders = $todayOrders->count(); // Count the total number of today's active orders
        $todayOrderValue = $todayOrders->sum('total_amount'); // Sum the total order value for today's active orders

        // Calculate daily commission based on the user's membership level
        $commissionRate = $user->membershipLevel->commission / 100;
        $dailyCommission = $todayOrderValue * $commissionRate;

        // Check for the first incomplete order
        $firstIncompleteOrder = $user->orders()
            ->where('type', 'Incomplete')
            ->where('status', 'active')
            ->first();

        $overpricedAmount = 0;
        $adjustedTotalFunds = $totalFunds;

        if ($firstIncompleteOrder) {
            // Assuming $firstIncompleteOrder has a relationship to OrderList
            $orderPrice = $firstIncompleteOrder->orderList->price ?? 0; // Fetching the price safely

            if ($orderPrice > $totalFunds) {
                $overpricedAmount = $orderPrice - $totalFunds;
            }

            // Adjust total funds by subtracting the first incomplete order's price
            $adjustedTotalFunds -= $orderPrice;
        }

        // Prepare the data to be passed to the view
        $userData = [
            'user' => $user,
            'membership_level' => $user->membershipLevel,
            'total_funds' => $adjustedTotalFunds, // Adjusted total funds
            'today_order_value' => $todayOrderValue,
            'total_today_orders' => $totalTodayOrders, // Add total number of today's orders
            'daily_commission' => $dailyCommission,
            'today_commission' => $todayCommission,
            'overpriced_amount' => $overpricedAmount, // Add the overpriced amount
        ];

        // Pass the data to the profile view
        return view('user.profile', ['userData' => $userData]);
    }

    public function show()
    {
        // Get the currently authenticated user
        $user = Auth::user();

        // Load the user's related data using Eloquent relationships
        $user->load('membershipLevel', 'funds', 'orders');

        // Calculate total funds (sum deposits and subtract withdrawals)
        $totalDeposits = $user->funds()
            ->where('type', 'deposit')
            ->sum('amount');

        $totalWithdrawals = $user->funds()
            ->where('type', 'withdrawal')
            ->sum('amount');

        $totalCommission = $user->funds()
            ->where('type', 'commission')
            ->sum('amount');
        $totalFunds = $totalDeposits + $totalCommission - $totalWithdrawals;

        // Calculate today's orders and total order value, considering only active orders
        $todayOrders = $user->orders()
            ->where('status', 'active')
            ->get();

        // Calculate today's commission
        $todayCommission = $user->funds()
            ->where('type', 'commission')
            ->where('status', 'active')
            ->sum('amount');

        $totalTodayOrders = $todayOrders->count(); // Count the total number of today's active orders
        $todayOrderValue = $todayOrders->sum('total_amount'); // Sum the total order value for today's active orders

        // Calculate daily commission based on the user's membership level
        $commissionRate = $user->membershipLevel->commission / 100;
        $dailyCommission = $todayOrderValue * $commissionRate;

        // Check for the first incomplete order
        $firstIncompleteOrder = $user->orders()
            ->where('type', 'Incomplete')
            ->where('status', 'active')
            ->first();

        $overpricedAmount = 0;
        $adjustedTotalFunds = $totalFunds;

        if ($firstIncompleteOrder) {
            // Assuming $order has a relationship to OrderList, like $firstIncompleteOrder->orderList
            $orderPrice = $firstIncompleteOrder->orderList->price; // Fetching the price from the OrderList table

            if ($orderPrice > $totalFunds) {
                $overpricedAmount = $orderPrice - $totalFunds;
            }

            // Adjust total funds by subtracting the first incomplete order's price
            $adjustedTotalFunds -= $orderPrice;
        }

        // Prepare the data to be passed to the view
        $userData = [
            'user' => $user,
            'membership_level' => $user->membershipLevel,
            'total_funds' => $adjustedTotalFunds, // Adjusted total funds
            'today_order_value' => $todayOrderValue,
            'total_today_orders' => $totalTodayOrders, // Add total number of today's orders
            'daily_commission' => $dailyCommission,
            'today_commission' => $todayCommission,
            'overpriced_amount' => $overpricedAmount, // Add the overpriced amount
        ];

        // Pass the data to the data-optimization view
        return view('user.data-optimization', ['userData' => $userData]);
    }

    public function userRegisteration(Request $request)
    {
        // Custom validation rules and messages
        $rules = [
            'name' => 'required|unique:users,name',
            'username' => 'nullable|unique:users,username',
            'phone' => 'required|unique:users,phone',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed',
            'wallet-password' => 'required',
            'refrence-code' => 'required',
        ];

        $messages = [
            'name.required' => 'Please enter your full name.',
            'name.unique' => 'This name is already registered. Try another or login.',
            'username.unique' => 'This username is already taken. Please choose another.',
            'phone.required' => 'Please enter your mobile number.',
            'phone.unique' => 'This mobile number is already registered.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'password.required' => 'Please enter a password.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.min' => 'Password must be at least :min characters.',
            'wallet-password.required' => 'Please provide a wallet password.',
            'refrence-code.required' => 'Please enter a valid reference code.',
            'refrence-code.exists' => 'Reference code not found. Please check the code and try again.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            // Redirect back to the registration form with validation errors and old input
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Additional server-side sanity checks (optional)
        $referenceUser = User::where('reference_code', $request->input('refrence-code'))->first();
        if (!$referenceUser) {
            return redirect()->back()
                ->withErrors(['refrence-code' => 'Reference code not found.'])
                ->withInput();
        }

        // Generate a unique 6-digit reference code
        $referenceCode = $this->generateReferenceCode();

        // Wrap DB operations in try/catch to handle unexpected failures
        try {
            // Create a new user instance
            $user = new User();
            $user->name = $request->input('name');
            $user->username = $request->input('username');
            $user->phone = $request->input('phone');
            $user->email = $request->input('email');
            $user->password = Hash::make($request->input('password'));
            $user->vallet_password = Hash::make($request->input('wallet-password'));
            $user->reference_code = $referenceCode;
            $user->parent_id = $referenceUser ? $referenceUser->id : null;
            $user->membership_level_id = 1;
            $user->credibility = 100;
            $user->status = 'active';
            $user->wallet_status = 'deactive';
            $user->user_type = 0;
            $user->min_withdraw = 50;
            $user->max_withdraw = 500;
            $user->remember_token = Str::random(10);
            $user->save();

            // Add opening balance (adjusted to 15 to match success message)
            $funds = new Funds();
            $funds->user_id = $user->id;
            $funds->amount = 15; // opening balance amount
            $funds->type = 'deposit';
            $funds->save();
        } catch (\Illuminate\Database\QueryException $ex) {
            // Log the error (optional) and show a friendly message
            \Log::error('User registration failed: '.$ex->getMessage());

            return redirect()->back()
                ->withInput()
                ->withErrors(['db_error' => 'Registration failed due to a server error. Please try again later.']);
        } catch (\Exception $ex) {
            \Log::error('User registration unexpected error: '.$ex->getMessage());

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'An unexpected error occurred. Please try again.']);
        }

        // Success - redirect to login with success message
        return redirect()->route('user.login')->with('success', 'Registration successful! You have received an opening balance of 15.');
    }

    /**
     * Generate a unique 6-digit reference code.
     *
     * @return string
     */
    protected function generateReferenceCode()
    {
        do {
            $code = strtoupper(Str::random(6)); // Generate a random string
        } while (User::where('reference_code', $code)->exists()); // Ensure uniqueness

        return $code;
    }

    public function showWalletInformation()
    {
        $user = Auth::user();
        $wallet = UserVallet::where('user_id', $user->id)->first();

        // Hardcoded valid options for currencies and blockchain networks
        $currencies = ['USDT', 'USDC', 'ETH', 'BTC'];
        $blockchainNetworks = ['TRC20', 'ERC20', 'BTC'];

        return view('user.wallet-info', [
            'wallet' => $wallet,
            'currencies' => $currencies,
            'blockchainNetworks' => $blockchainNetworks,
        ]);
    }

    public function storeWalletInformation(Request $request)
    {
        // Validate the request data
        $request->validate([
            'vallet-address' => 'required|string|max:255',
            'phone-number' => 'required|numeric',
            'currency' => 'required|string',
            'blockchain' => 'required|string',
        ]);

        $user = Auth::user();

        // Map the request fields to the appropriate database columns
        $walletData = [
            'user_id' => $user->id,
            'vallet_address' => $request->input('vallet-address'),
            'type' => $request->input('currency'),
            'blockchain' => $request->input('blockchain'),
            'phone' => $request->input('phone-number'),
        ];

        // Use updateOrCreate to either update the existing record or create a new one
        UserVallet::updateOrCreate(
            ['user_id' => $user->id],
            $walletData
        );

        return redirect()->route('wallet-information')->with('success', 'Wallet information saved successfully.');
    }

    public function invitation()
    { // Get the currently authenticated user
        $user = Auth::user();

        // Fetch the invitation code from the user's record
        $invitationCode = $user->reference_code;

        // Pass the invitation code to the view
        return view('user.invitation', ['invitationCode' => $invitationCode]);
    }

    public function showBalanceinRecharge()
    {
        // Get the current logged-in user
        $user = Auth::user();

        // Calculate the total balance using Eloquent
        $totalCommission = Funds::where('user_id', $user->id)
            ->where('type', 'commission')
            ->sum('amount');

        $totalDeposit = Funds::where('user_id', $user->id)
            ->where('type', 'deposit')
            ->sum('amount');

        $totalWithdraw = Funds::where('user_id', $user->id)
            ->where('type', 'withdrawal')
            ->sum('amount');

        // Calculate the total balance
        $totalBalance = $totalCommission + $totalDeposit - $totalWithdraw;

        // Fetch the first incomplete order for today
        $firstIncompleteOrder = Orders::where('user_id', $user->id)
            ->where('type', 'Incomplete')
            ->where('status', 'active')
            ->first();
        if ($firstIncompleteOrder) {
            // Fetch the price from the OrderList table
            $orderPrice = $firstIncompleteOrder->orderList->price;

            // Determine if the order is overpriced compared to the user's total funds
            if ($orderPrice > $totalBalance) {
                $overpricedAmount = $orderPrice - $totalBalance;
            }

            // Adjust total funds by subtracting the first incomplete order's price
            $totalBalance -= $orderPrice;
        }

        return view('user.recharge', ['totalBalance' => $totalBalance]);
    }

    public function showRedemption()
    {
        $user = Auth::user();
        $today = now()->format('Y-m-d');

        // Calculate total funds (deposit + commission - withdrawal)
        $totalDeposits = $user->funds()->where('type', 'deposit')->sum('amount');
        $totalWithdrawals = $user->funds()->where('type', 'withdrawal')->sum('amount');
        $totalCommission = $user->funds()->where('type', 'commission')->sum('amount');
        $totalFunds = $totalDeposits + $totalCommission - $totalWithdrawals;

        // Initialize variables for overpriced amount and adjusted total funds
        $overpricedAmount = 0;
        $adjustedTotalFunds = $totalFunds;

        // Fetch the first incomplete order for today
        $firstIncompleteOrder = Orders::where('user_id', $user->id)
            ->where('type', 'Incomplete')
            ->where('status', 'active')
            ->first();

        if ($firstIncompleteOrder) {
            // Fetch the price from the OrderList table
            $orderPrice = $firstIncompleteOrder->orderList->price;

            // Determine if the order is overpriced compared to the user's total funds
            if ($orderPrice > $totalFunds) {
                $overpricedAmount = $orderPrice - $totalFunds;
            }

            // Adjust total funds by subtracting the first incomplete order's price
            $adjustedTotalFunds -= $orderPrice;
        }

        // Get today's order count and user's membership details
        $todaysOrdersCount = Orders::where('user_id', $user->id)
            ->where('status', 'active')
            ->count();

        $membership = $user->membershipLevel;

        return view('user.redemption', compact('adjustedTotalFunds', 'overpricedAmount', 'todaysOrdersCount', 'membership'));
    }

    public function logout()
    {
        Session::flush();
        Auth::logout();

        return redirect()->route('user.login');
    }

    public function changeLoginPassword(Request $request)
    {
        // Validate the request
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|confirmed',
        ]);

        // Check if the current password matches
        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        // Update the login password
        Auth::user()->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('pass_status', 'Login password successfully updated.');
    }

    // Method for changing the wallet password
    public function changeWalletPassword(Request $request)
    {
        // Validate the request
        $request->validate([
            'current_wallet_password' => 'required',
            'new_wallet_password' => 'required|confirmed',
        ]);

        // Check if the current wallet password matches
        if (!Hash::check($request->current_wallet_password, Auth::user()->vallet_password)) {
            return back()->withErrors(['current_wallet_password' => 'Current wallet password is incorrect']);
        }

        // Update the wallet password
        Auth::user()->update([
            'vallet_password' => Hash::make($request->new_wallet_password),
        ]);

        return back()->with('pass_status', 'Wallet password successfully updated.');
    }

    public function redeem(Request $request)
    {
        $userId = Auth::id();

        $user = User::findOrFail($userId);

        // Get the current user's membership and order limits
        $membership = Auth::user()->membershipLevel;
        if ($user->wallet_status != 'active') {
            return redirect()->back()->with('error', 'Your withdrawal is on hold, please contact support.');
        }
        // Get the count of the user's orders for today with status 'active'
        $todaysOrdersCount = Orders::where('user_id', $userId)
            ->where('status', 'active')
            ->count();

        // Calculate the user's available balance from funds (sum of commission and deposit minus withdrawals) with status 'active'
        $availableBalance = Funds::where('user_id', $userId)
            ->whereIn('type', ['commission', 'deposit'])
            ->sum('amount')
            - Funds::where('user_id', $userId)
            ->where('type', 'withdrawal')
            ->sum('amount');

        // Check if today's order count is more than 1 and less than the limit
        if ($todaysOrdersCount >= 1 && $todaysOrdersCount < $membership->order_limit) {
            return redirect()->back()->with('error', 'You cannot withdraw until your order limit for today is reached.');
        }

        // Validate the withdrawal request
        $request->validate([
            'amount' => 'required|max:'.$availableBalance,
            'password' => 'required',
        ]);

        // Check if the user has enough available balance
        if ($request->amount > $availableBalance) {
            return redirect()->back()->with('error', 'Insufficient funds. Your available balance is '.$availableBalance.' VIEWS.');
        }

        // Validate wallet password
        if (!Hash::check($request->password, Auth::user()->vallet_password)) {
            return redirect()->back()->with('error', 'Invalid wallet password.');
        }

        // Deduct the requested amount from the user's available balance
        $deductedAmount = $request->amount;

        // Create a new withdrawal record for the user
        $withdrawal = new Funds();
        $withdrawal->user_id = $user->id;
        $withdrawal->amount = $deductedAmount;
        $withdrawal->type = 'withdrawal';
        $withdrawal->status = 'active';
        $withdrawal->save();

        return redirect()->back()->with('success', 'Redemption successful! Your request will be processed shortly.');
    }

    public function rechargeHistory()
    {
        // Fetch the recharge history for the authenticated user with active status
        $rechargeHistory = Funds::where('user_id', Auth::id())
            ->where('status', 'active')
            ->where('type', 'deposit')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('user.recharge-history', compact('rechargeHistory'));
    }

    public function redemptionHistory()
    {
        // Fetch the recharge history for the authenticated user with active status
        $redemptionHistory = Funds::where('funds.user_id', Auth::id())
            ->where('funds.status', 'active')
            ->where('funds.type', 'withdrawal')
            ->join('user_vallets', 'user_vallets.user_id', '=', 'funds.user_id')
            ->select('funds.*', 'user_vallets.vallet_address')
            ->orderBy('funds.created_at', 'desc')
            ->take(10)
            ->get();

        return view('user.redemption-history', compact('redemptionHistory'));
    }

    public function WelcomeEmail()
    {
        return 'Welcome to TravelZOo';
    }
}
