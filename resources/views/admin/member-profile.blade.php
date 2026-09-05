@extends('admin.layout.master')

@section('title', 'Member Profile')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{ $user->name }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('administration') }}">Members</a></li>
                    <li class="breadcrumb-item active">{{ $user->name }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
<div class="container-fluid">

    @if (session('funds_success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('funds_success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <div class="row">
        <!-- Left: profile summary + actions -->
        <div class="col-md-4">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile text-center">
                    <img class="profile-user-img img-fluid img-circle"
                         src="{{ $user->profile_photo_url }}" alt="User profile picture">

                    <h3 class="profile-username text-center">{{ $user->name }}</h3>

                    <p class="text-center">
                        <span class="badge badge-{{ $user->status == 'active' ? 'success' : 'danger' }}">
                            {{ ucfirst($user->status) }}
                        </span>
                        <span class="badge badge-{{ $user->wallet_status == 'active' ? 'success' : 'danger' }}">
                            Wallet {{ ucfirst($user->wallet_status) }}
                        </span>
                        @if($user->user_type == 1)
                            <span class="badge badge-primary">Admin</span>
                        @elseif($user->user_type == 2)
                            <span class="badge badge-info">Moderator</span>
                        @endif
                    </p>

                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Member ID</b> <a class="float-right">{{ $user->id }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Phone</b> <a class="float-right">{{ $user->phone }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Email</b> <a class="float-right">{{ $user->email }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Membership</b> <a class="float-right">{{ $user->membershipLevel->level_name ?? 'N/A' }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Credibility</b> <a class="float-right">{{ $user->credibility }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Personal Reference Code</b> <a class="float-right">{{ $user->reference_code }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Min / Max Withdraw</b> <a class="float-right">{{ number_format($user->min_withdraw, 2) }} / {{ number_format($user->max_withdraw, 2) }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Last IP</b> <a class="float-right">{{ $user->last_ip_address ?? 'N/A' }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Last Location</b> <a class="float-right">{{ $user->last_location ?? 'N/A' }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Registered On</b> <a class="float-right">{{ $user->created_at->format('Y-m-d H:i') }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Last Updated</b> <a class="float-right">{{ $user->updated_at->format('Y-m-d H:i') }}</a>
                        </li>
                    </ul>

                    <a href="{{ url('/update-user/'.$user->id) }}" class="btn btn-danger btn-block"><i class="fas fa-edit"></i> Edit Member</a>
                    <a href="{{ url('/add-debit/'.$user->id) }}" class="btn btn-primary btn-block"><i class="fas fa-coins"></i> Add / Deduct Funds</a>
                    <a href="{{ url('/vallet-information/'.$user->id) }}" class="btn btn-secondary btn-block"><i class="fas fa-wallet"></i> Wallet Information</a>
                    <a href="{{ route('reset.orders', $user->id) }}" class="btn btn-outline-primary btn-block"><i class="fas fa-list-ol"></i> Setup Selected Orders</a>
                    <a href="{{ route('order.queue', $user->id) }}" class="btn btn-outline-secondary btn-block"><i class="fas fa-boxes"></i> View Order Queue</a>
                    <a href="{{ route('generate.orders', $user->id) }}" class="btn btn-outline-success btn-block" onclick="return confirm('Generate this user\'s remaining orders for today?');"><i class="fas fa-play"></i> Generate Today's Orders</a>
                    <a href="{{ route('reset.todays.orders', $user->id) }}" class="btn btn-outline-warning btn-block" onclick="return confirm('Reset today\'s orders and funds?');"><i class="fas fa-undo"></i> Reset Today's Orders</a>
                    @if(Auth::user()->user_type == 1)
                        <a href="{{ route('admin.chats.show', $user->id) }}" class="btn btn-outline-info btn-block"><i class="fas fa-comments"></i> Open Chat</a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right: financials, orders, and funds history -->
        <div class="col-md-8">

            <!-- Financial summary -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="info-box">
                        <span class="info-box-icon bg-success"><i class="fas fa-wallet"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Balance</span>
                            <span class="info-box-number">${{ number_format($totalFunds, 2) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="fas fa-hand-holding-usd"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Deposits</span>
                            <span class="info-box-number">${{ number_format($totalDeposits, 2) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="info-box">
                        <span class="info-box-icon bg-warning"><i class="fas fa-coins"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Commission</span>
                            <span class="info-box-number">${{ number_format($totalCommission, 2) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="info-box">
                        <span class="info-box-icon bg-danger"><i class="fas fa-minus-circle"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Withdrawals</span>
                            <span class="info-box-number">${{ number_format($totalWithdrawals, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @if($pendingDeposits > 0 || $pendingWithdrawals > 0)
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-warning">
                        <i class="fas fa-clock"></i>
                        @if($pendingDeposits > 0) ${{ number_format($pendingDeposits, 2) }} in deposits pending approval. @endif
                        @if($pendingWithdrawals > 0) ${{ number_format($pendingWithdrawals, 2) }} in withdrawals pending approval. @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Wallet address -->
            <div class="card">
                <div class="card-header"><h3 class="card-title">Wallet Information</h3></div>
                <div class="card-body">
                    @if($wallet)
                        <p><b>Address:</b> {{ $wallet->vallet_address }}</p>
                        <p><b>Type:</b> {{ $wallet->type }}</p>
                        <p><b>Blockchain:</b> {{ $wallet->blockchain ?? 'N/A' }}</p>
                        <p class="mb-0"><b>Phone on file:</b> {{ $wallet->phone }}</p>
                    @else
                        <p class="mb-0 text-muted">No wallet information on file.</p>
                    @endif
                </div>
            </div>

            <!-- Orders -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Orders ({{ $ordersCount }} total &middot; {{ $completedOrdersCount }} completed &middot; {{ $incompleteOrdersCount }} pending)</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table m-0">
                            <thead>
                                <tr><th>Order</th><th>Price</th><th>Commission</th><th>Total</th><th>Type</th><th>Date</th></tr>
                            </thead>
                            <tbody>
                                @forelse($ordersHistory as $order)
                                    <tr>
                                        <td>{{ $order->orderList->title ?? ('#'.$order->order_id) }}</td>
                                        <td>${{ number_format($order->price, 2) }}</td>
                                        <td>${{ number_format($order->commission, 2) }}</td>
                                        <td>${{ number_format($order->total_amount, 2) }}</td>
                                        <td>
                                            <span class="badge badge-{{ $order->type == 'Complete' ? 'success' : 'warning' }}">
                                                {{ $order->type }}
                                            </span>
                                        </td>
                                        <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-muted">No orders yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($ordersCount > $ordersHistory->count())
                    <div class="card-footer text-center">
                        <a href="{{ route('order.queue', $user->id) }}">View full order queue &rarr;</a>
                    </div>
                @endif
            </div>

            <!-- Funds history -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Funds History</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table m-0">
                            <thead>
                                <tr><th>Type</th><th>Amount</th><th>Status</th><th>Date</th></tr>
                            </thead>
                            <tbody>
                                @forelse($fundsHistory as $fund)
                                    <tr>
                                        <td>{{ ucfirst($fund->type) }}</td>
                                        <td>${{ number_format($fund->amount, 2) }}</td>
                                        <td>
                                            <span class="badge badge-{{ $fund->status == 'active' ? 'success' : ($fund->status == 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($fund->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $fund->created_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-muted">No funds activity yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($fundsHistory->count() >= 25)
                    <div class="card-footer text-center">
                        <a href="{{ route('user.recharge.history', $user->id) }}">View full funds history &rarr;</a>
                    </div>
                @endif
            </div>

        </div>
    </div>

</div>
</section>
@endsection
