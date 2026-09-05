<?php

namespace App\Http\Controllers;
use App\Models\Funds;
use App\Models\Orders;
use App\Models\Members;
use App\Models\Membership;
use App\Models\OrderList;
use App\Models\UserVallet;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;



class ProfileController extends Controller
{
    /**
     * User dashboard ("Home"). Moved here from an inline @php block that
     * used to live directly in resources/views/user/home.blade.php — same
     * queries and calculations, unchanged, just relocated out of the view.
     */
    public function dashboard()
    {
        $user = Auth::user();

        // Balance calculation
        $totalDeposits = $user->funds()->where('type', 'deposit')->whereIn('status', ['active', 'deactive'])->sum('amount');
        $totalWithdrawals = $user->funds()->where('type', 'withdrawal')->whereIn('status', ['active', 'deactive'])->sum('amount');
        $totalCommission = $user->funds()->where('type', 'commission')->whereIn('status', ['active', 'deactive'])->sum('amount');
        $totalBalance = $totalDeposits + $totalCommission - $totalWithdrawals;

        // Oldest first, matching the order the user will actually work on next.
        // The account balance shown to the user is always the real available
        // amount — it's never reduced for display. A pending order that costs
        // more than that balance instead surfaces as a separate shortfall
        // notification (see $overpricedAmount below), regardless of who
        // generated the order.
        $firstIncompleteOrder = $user->orders()->where('type', 'Incomplete')->where('status', 'active')->orderBy('id', 'asc')->first();
        $overpricedAmount = 0;
        if ($firstIncompleteOrder) {
            // Prefer the price frozen on the order itself (set when it was
            // generated) over the OrderList catalog price, which may have
            // changed since. The shortfall threshold is the item price alone
            // — commission is what the platform pays the user on completion,
            // not something they need balance to cover.
            $orderPrice = $firstIncompleteOrder->price ?? $firstIncompleteOrder->orderList->price;

            if ($orderPrice > $totalBalance) {
                $overpricedAmount = $orderPrice - $totalBalance;
            }
        }

        // Total Revenue (all time commission)
        $totalRevenue = $user->funds()->where('type', 'commission')->sum('amount');

        // Today's Commission (commission from funds with active status)
        $todayCommission = $user->funds()->where('type', 'commission')->where('status', 'active')->sum('amount');

        // Completed orders count (active completed orders)
        $completedOrdersCount = $user->orders()->where('type', 'Complete')->where('status', 'active')->count();

        // Order Limit
        $orderLimit = $user->membershipLevel->order_limit;

        // Earnings overview chart: real daily commission totals for the last
        // 30 days (grouped in one query), sliced down to 7 for the 7D view.
        $dailyCommissionByDate = $user->funds()
            ->where('type', 'commission')
            ->whereIn('status', ['active', 'deactive'])
            ->where('created_at', '>=', now()->subDays(29)->startOfDay())
            ->selectRaw('DATE(created_at) as day, SUM(amount) as total')
            ->groupBy('day')
            ->pluck('total', 'day');

        $dailyEarnings30 = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $dailyEarnings30[] = (float) ($dailyCommissionByDate[$date] ?? 0);
        }
        $dailyEarnings7 = array_slice($dailyEarnings30, -7);

        $earningsChart7d = $this->buildChartPaths($dailyEarnings7);
        $earningsChart30d = $this->buildChartPaths($dailyEarnings30);

        // Same 30/7-day breakdown for completed tasks (orders), so the card
        // can show a real amount + task count alongside the graph, not just
        // a line.
        $dailyTasksByDate = $user->orders()
            ->where('type', 'Complete')
            ->where('status', 'active')
            ->where('created_at', '>=', now()->subDays(29)->startOfDay())
            ->selectRaw('DATE(created_at) as day, COUNT(*) as total')
            ->groupBy('day')
            ->pluck('total', 'day');

        $dailyTasks30 = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $dailyTasks30[] = (int) ($dailyTasksByDate[$date] ?? 0);
        }
        $dailyTasks7 = array_slice($dailyTasks30, -7);

        $earningsTotal7d = array_sum($dailyEarnings7);
        $earningsTotal30d = array_sum($dailyEarnings30);
        $tasksTotal7d = array_sum($dailyTasks7);
        $tasksTotal30d = array_sum($dailyTasks30);

        $orderListItems = OrderList::all();

        return view('user.home', compact(
            'user',
            'totalBalance',
            'overpricedAmount',
            'totalRevenue',
            'todayCommission',
            'completedOrdersCount',
            'orderLimit',
            'earningsChart7d',
            'earningsChart30d',
            'earningsTotal7d',
            'earningsTotal30d',
            'tasksTotal7d',
            'tasksTotal30d',
            'orderListItems'
        ));
    }

    /**
     * Builds an SVG line + fill path (viewBox "0 0 100 40") from a flat
     * array of values, oldest first. Used by the dashboard earnings chart.
     */
    private function buildChartPaths(array $values)
    {
        $count = count($values);
        $max = $count > 0 ? max($values) : 0;
        $max = $max > 0 ? $max : 1; // avoid division by zero when every value is 0

        $points = [];
        foreach ($values as $i => $value) {
            $x = $count > 1 ? ($i / ($count - 1)) * 100 : 50;
            $y = 38 - (($value / $max) * 34);
            $points[] = round($x, 2) . ' ' . round($y, 2);
        }

        if (empty($points)) {
            return ['line' => 'M 0 38 L 100 38', 'fill' => 'M 0 38 L 100 38 L 100 40 L 0 40 Z'];
        }

        $line = 'M ' . $points[0];
        if (count($points) > 1) {
            $line .= ' L ' . implode(' L ', array_slice($points, 1));
        }
        $firstX = explode(' ', $points[0])[0];
        $lastX = explode(' ', $points[count($points) - 1])[0];
        $fill = $line . " L {$lastX} 40 L {$firstX} 40 Z";

        return ['line' => $line, 'fill' => $fill];
    }

    public function index()
    {
        // Get the currently authenticated user
        $user = Auth::user();

        // Load the user's related data using Eloquent relationships
        $user->load('membershipLevel', 'funds', 'orders');

        // Calculate total funds (sum deposits and subtract withdrawals)
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

        $todayOrderValue = $todayOrders->sum('total_amount');

        // Calculate daily commission based on the user's membership level
        $commissionRate = $user->membershipLevel->commission / 100;
        $dailyCommission = $todayOrderValue * $commissionRate;

        // Prepare the data to be passed to the view
        $userData = [
            'user' => $user,
            'membership_level' => $user->membershipLevel,
            'total_funds' => $totalFunds,
            'today_order_value' => $todayOrderValue,
            'daily_commission' => $dailyCommission,
            'today_commission' => $todayCommission,
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

        // Orders Completed should count the same thing the dashboard counts:
        // completed orders only, not the one still-pending order too.
        $totalTodayOrders = $user->orders()
            ->where('type', 'Complete')
            ->where('status', 'active')
            ->count();
        $todayOrderValue = $todayOrders->sum('total_amount'); // Sum the total order value for today's active orders

        // Calculate daily commission based on the user's membership level
        $commissionRate = $user->membershipLevel->commission / 100;
        $dailyCommission = $todayOrderValue * $commissionRate;

        // Check for the first incomplete order (oldest first, matching the
        // order they'll actually work on next)
        $firstIncompleteOrder = $user->orders()
            ->where('type', 'Incomplete')
            ->where('status', 'active')
            ->orderBy('id', 'asc')
            ->first();

        // The balance shown to the user is always the real available amount
        // — it's never reduced for display. A pending order that costs more
        // than that balance instead surfaces as a separate shortfall
        // ($overpricedAmount), regardless of who generated the order.
        $overpricedAmount = 0;
        $adjustedTotalFunds = $totalFunds;

        if ($firstIncompleteOrder) {
            // Prefer the price frozen on the order itself (set when it was
            // generated) over the OrderList catalog price, which may have
            // changed since. The shortfall threshold is the item price
            // alone — commission is what the platform pays the user on
            // completion, not something they need balance to cover.
            $orderPrice = $firstIncompleteOrder->price ?? $firstIncompleteOrder->orderList->price;

            if ($orderPrice > $totalFunds) {
                $overpricedAmount = $orderPrice - $totalFunds;
            }
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
        // The single reference code identifies the existing parent account.
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users,username|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed',
            'wallet-password' => 'required',
            'refrence-code' => 'required|exists:users,reference_code',
        ], [
            'refrence-code.required' => 'Please enter a valid reference code.',
            'refrence-code.exists' => 'Reference code not found. Please check the code and try again.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $username = $request->input('username');
        $referenceUser = User::where('reference_code', $request->input('refrence-code'))->first();

        if (!$referenceUser) {
            return redirect()->back()
                ->withErrors(['refrence-code' => 'Reference code not found. Please check the code and try again.'])
                ->withInput();
        }

        // Create a new user. Built via direct property assignment (not
        // User::create()) because this table also requires username, email,
        // and remember_token — none of which are in User::$fillable, and
        // adding them there would widen mass-assignment exposure elsewhere.
        $user = DB::transaction(function () use ($request, $referenceUser, $username) {
            $user = new User();
            $user->name = $username;
            $user->username = $username;
        // The users table requires a unique-looking, non-null email even
        // though nothing in the app currently reads it (login is by
        // username, not email).
            $user->email = $request->input('email');
        // Phone is no longer collected on this form, but the column is still
        // NOT NULL and unique — auto-generate a placeholder instead.
            $user->phone = 'N/A-' . strtoupper(Str::random(8));
            $user->password = Hash::make($request->input('password'));
            $user->vallet_password = Hash::make($request->input('wallet-password'));
        // NOT NULL with no default on this table — must be set explicitly.
            $user->remember_token = Str::random(10);
        // Generate the new user's personal reference code and link the
        // account to the user who owns the submitted reference code.
            do {
                $user->reference_code = strtoupper(Str::random(6));
            } while (User::where('reference_code', $user->reference_code)->exists());
            $user->parent_id = $referenceUser->id;
            $user->membership_level_id = 1;
            $user->credibility = 100;
            $user->status = 'active';
            $user->user_type = 0;
            $user->min_withdraw = 50;
            $user->max_withdraw = 3000;
            $user->save();

            $user->funds()->create([
                'amount' => 15,
                'type' => 'deposit',
                'status' => 'active',
            ]);

            return $user;
        });

        // Redirect to login page
        return redirect()->route('user.login');
    }

    public function showWalletInformation()
    {
        $user = Auth::user();
        $wallet = UserVallet::where('user_id', $user->id)->first();

        return view('user.wallet-info', ['wallet' => $wallet]);
    }

    public function storeWalletInformation(Request $request)
    {
        $request->validate([
            'vallet-address' => 'required|string|max:255',
            'wallet-type' => 'required|string',
            'blockchain' => 'required|string|in:TRON,Ethereum,Bitcoin,BSC',
        ]);

        $user = Auth::user();

        // Map the request fields to the appropriate database columns
        $walletData = [
            'user_id' => $user->id,
            'vallet_address' => $request->input('vallet-address'),
            'type' => $request->input('wallet-type'),
            'blockchain' => $request->input('blockchain'),
        ];

        // Phone is no longer collected here, but the column is still NOT
        // NULL with no default — it must be included in the very insert
        // that creates the row, or the query fails outright. Only set a
        // placeholder when there's no existing wallet yet; never overwrite
        // an existing value (real or placeholder) on a later edit.
        if (!UserVallet::where('user_id', $user->id)->exists()) {
            $walletData['phone'] = 'N/A-' . strtoupper(Str::random(8));
        }

        // Use updateOrCreate to either update existing record or create a new one
        UserVallet::updateOrCreate(
            ['user_id' => $user->id],
            $walletData
        );

        return redirect()->route('wallet-information')->with('success', 'Wallet information saved successfully.');
    }
    public function showBalanceinRecharge()
    {
        // Get the current logged-in user
        $user = Auth::user();

        // Calculate the total balance using Eloquent
        $totalCommission = Funds::where('user_id', $user->id)
                                 ->where('type', 'commission')
                                 ->whereIn('status', ['active', 'deactive'])
                                 ->sum('amount');

        $totalDeposit = Funds::where('user_id', $user->id)
                              ->where('type', 'deposit')
                              ->whereIn('status', ['active', 'deactive'])
                              ->sum('amount');

        $totalWithdraw = Funds::where('user_id', $user->id)
                               ->where('type', 'withdrawal')
                               ->whereIn('status', ['active', 'deactive'])
                               ->sum('amount');

        // Calculate the total balance
        $totalBalance = $totalCommission + $totalDeposit - $totalWithdraw;

        // The balance shown to the user is always the real available amount
        // — it's never reduced for display. A pending order that costs more
        // than that balance instead surfaces as a separate shortfall
        // ($overpricedAmount), regardless of who generated the order.
        $overpricedAmount = 0;

        // Fetch the first incomplete order for today (oldest first, matching
        // the order they'll actually work on next)
        $firstIncompleteOrder = Orders::where('user_id', $user->id)
            ->where('type', 'Incomplete')
            ->where('status', 'active')
            ->orderBy('id', 'asc')
            ->first();

        if ($firstIncompleteOrder) {
            // Prefer the price frozen on the order itself (set when it was
            // generated) over the OrderList catalog price, which may have
            // changed since. The shortfall threshold is the item price
            // alone — commission is what the platform pays the user on
            // completion, not something they need balance to cover.
            $orderPrice = $firstIncompleteOrder->price ?? $firstIncompleteOrder->orderList->price;

            if ($orderPrice > $totalBalance) {
                $overpricedAmount = $orderPrice - $totalBalance;
            }
        }

        $wallet = \App\Models\PlatformWallet::first();
        if (!$wallet) {
            $wallet = (object) [
                'wallet_type' => 'USDT TRC20',
                'wallet_address' => 'TBir8eHDc8quGkkvM1mNav4deeqvJzhq',
                'qr_code' => 'images/deposit_qr.png',
            ];
        }

        return view('user.recharge', [
            'totalBalance' => $totalBalance,
            'wallet' => $wallet,
            'overpricedAmount' => $overpricedAmount,
        ]);
    }

    public function showRedemption()
    {
        $user = Auth::user();
        $today = now()->format('Y-m-d');

        // Calculate total funds (deposit + commission - withdrawal)
        $totalDeposits = $user->funds()->where('type', 'deposit')->whereIn('status', ['active', 'deactive'])->sum('amount');
        $totalWithdrawals = $user->funds()->where('type', 'withdrawal')->whereIn('status', ['active', 'deactive'])->sum('amount');
        $totalCommission = $user->funds()->where('type', 'commission')->whereIn('status', ['active', 'deactive'])->sum('amount');
        $totalFunds = $totalDeposits + $totalCommission - $totalWithdrawals;

        // The balance shown to the user is always the real available amount
        // — it's never reduced for display. A pending order that costs more
        // than that balance instead surfaces as a separate shortfall
        // ($overpricedAmount), regardless of who generated the order.
        $overpricedAmount = 0;
        $adjustedTotalFunds = $totalFunds;

        // Fetch the first incomplete order for today (oldest first, matching
        // the order they'll actually work on next)
        $firstIncompleteOrder = Orders::where('user_id', $user->id)
            ->where('type', 'Incomplete')
            ->where('status', 'active')
            ->orderBy('id', 'asc')
            ->first();

        if ($firstIncompleteOrder) {
            // Prefer the price frozen on the order itself (set when it was
            // generated) over the OrderList catalog price, which may have
            // changed since. The shortfall threshold is the item price
            // alone — commission is what the platform pays the user on
            // completion, not something they need balance to cover.
            $orderPrice = $firstIncompleteOrder->price ?? $firstIncompleteOrder->orderList->price;

            if ($orderPrice > $totalFunds) {
                $overpricedAmount = $orderPrice - $totalFunds;
            }
        }

        // Count of the user's completed orders (status active, type Complete).
        $todaysOrdersCount = Orders::where('user_id', $user->id)
            ->where('status', 'active')
            ->where('type', 'Complete')
            ->count();

        $membership = $user->membershipLevel;

        return view('user.redemption', compact('adjustedTotalFunds', 'overpricedAmount', 'todaysOrdersCount', 'membership'));
    }



    public function logout()
    {
        session::flush();
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
        // Count of the user's completed orders (status active, type Complete).
        // Not scoped to today's date — a user may take more than one day to
        // finish their order queue, so we count completed orders overall
        // rather than only today's.
        $todaysOrdersCount = Orders::where('user_id', $userId)
                                ->where('status', 'active')
                                ->where('type', 'Complete')
                                ->count();

        // Calculate the user's available balance from funds (sum of commission and deposit minus withdrawals) with status 'active'
        $availableBalance = Funds::where('user_id', $userId)
                                ->whereIn('type', ['commission', 'deposit'])
                                ->whereIn('status', ['active', 'deactive'])
                                ->sum('amount')
                            - Funds::where('user_id', $userId)
                                    ->where('type', 'withdrawal')
                                    ->whereIn('status', ['active', 'deactive'])
                                    ->sum('amount');

        // Check if today's order count is more than 1 and less than the limit
        if ($todaysOrdersCount >= 1 && $todaysOrdersCount < $membership->order_limit) {
            return redirect()->back()->with('error', 'You cannot withdraw until your order limit for today is reached.');
        }

        // Validate the withdrawal request
        $request->validate([
            'amount' => 'required|max:' . $availableBalance,
            'password' => 'required',
        ]);

        // Check if the user has enough available balance
        if ($request->amount > $availableBalance) {
            return redirect()->back()->with('error', 'Insufficient funds. Your available balance is $' . number_format($availableBalance, 2) . '.');
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
        $withdrawal->status = 'pending';
        $withdrawal->save();


        return redirect()->back()->with('success', 'Redemption successful! Your request will be processed shortly.');
    }

    public function submitRecharge(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'screenshot' => 'required|image|max:5120',
        ]);

        $user = Auth::user();

        $imageName = null;
        if ($request->hasFile('screenshot')) {
            $image = $request->file('screenshot');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('deposit_receipts'), $imageName);
        }

        Funds::create([
            'user_id' => $user->id,
            'amount' => $request->input('amount'),
            'type' => 'deposit',
            'status' => 'pending',
            'image' => $imageName ? 'deposit_receipts/' . $imageName : null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('recharge')->with('success', 'Deposit request submitted successfully! Pending approval.');
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


}
