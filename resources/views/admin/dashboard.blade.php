@extends('admin.layout.master')
@section('title', 'Dashboard')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Dashboard</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
<div class="container-fluid">

    <h5 class="text-muted mb-2">Today</h5>
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $todaysUsers }}</h3>
                    <p>New Users Today</p>
                </div>
                <div class="icon"><i class="fas fa-user-plus"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $todaysOrders }}</h3>
                    <p>Orders Today</p>
                </div>
                <div class="icon"><i class="fas fa-shopping-cart"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>${{ number_format($todaysDeposits, 2) }}</h3>
                    <p>Deposits Today</p>
                </div>
                <div class="icon"><i class="fas fa-dollar-sign"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>${{ number_format($todaysWithdrawals, 2) }}</h3>
                    <p>Withdrawals Today</p>
                </div>
                <div class="icon"><i class="fas fa-minus-circle"></i></div>
            </div>
        </div>
    </div>

    <h5 class="text-muted mb-2 mt-2">All-Time Totals</h5>
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="info-box">
                <span class="info-box-icon bg-primary"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Members</span>
                    <span class="info-box-number">{{ $totalMembers }}</span>
                    <span class="progress-description">
                        {{ $activeMembers }} active &middot; {{ $deactiveMembers }} deactivated
                    </span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="info-box">
                <span class="info-box-icon bg-secondary"><i class="fas fa-boxes"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Orders</span>
                    <span class="info-box-number">{{ $totalOrders }}</span>
                    <span class="progress-description">
                        {{ $completedOrders }} completed &middot; {{ $pendingOrders }} pending
                    </span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="info-box">
                <span class="info-box-icon bg-success"><i class="fas fa-hand-holding-usd"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Deposits</span>
                    <span class="info-box-number">${{ number_format($totalDeposits, 2) }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="info-box">
                <span class="info-box-icon bg-danger"><i class="fas fa-wallet"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Withdrawals</span>
                    <span class="info-box-number">${{ number_format($totalWithdrawals, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fas fa-coins"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Commission Paid</span>
                    <span class="info-box-number">${{ number_format($totalCommission, 2) }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <a href="{{ url('/admin/deposit-requests') }}" class="text-decoration-none">
                <div class="info-box">
                    <span class="info-box-icon bg-warning"><i class="fas fa-clock"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Pending Deposit Requests</span>
                        <span class="info-box-number">{{ $pendingDeposits }}</span>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-6">
            <a href="{{ url('/admin/redemption-requests') }}" class="text-decoration-none">
                <div class="info-box">
                    <span class="info-box-icon bg-warning"><i class="fas fa-clock"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Pending Withdrawal Requests</span>
                        <span class="info-box-number">{{ $pendingWithdrawals }}</span>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-6">
            <div class="info-box">
                <span class="info-box-icon bg-secondary"><i class="fas fa-crown"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Membership Levels</span>
                    <span class="info-box-number">{{ $membershipBreakdown->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Membership breakdown -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header border-transparent">
                    <h3 class="card-title">Members by Level</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table m-0">
                            <thead>
                                <tr>
                                    <th>Level</th>
                                    <th>Members</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($membershipBreakdown as $level)
                                    <tr>
                                        <td>{{ $level->level_name }}</td>
                                        <td>{{ $level->users_count }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="2">No membership levels found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Latest Users -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header border-transparent">
                    <h3 class="card-title">Latest Users</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table m-0">
                            <thead>
                                <tr>
                                    <th>User ID</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Registration Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestUsers as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td><a href="{{ route('member.show', $user->id) }}">{{ $user->name }}</a></td>
                                        <td>
                                            <span class="badge badge-{{ $user->status == 'active' ? 'success' : 'danger' }}">
                                                {{ ucfirst($user->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $user->created_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4">No users yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
</section>
@endsection
