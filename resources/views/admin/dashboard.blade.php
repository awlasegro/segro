@extends('admin.layout.master')
@section('title', 'Dashboard')

@section('content')
<div class="container">
    <div class="row">
        <!-- Today's Users Count -->
        <div class="col-md-3">
            <div class="info-box">
                <div class="info-box-icon bg-info">
                    <i class="fas fa-users"></i>
                </div>
                <div class="info-box-content">
                    <span class="info-box-text">Today's Users</span>
                    <span class="info-box-number">{{ $todaysUsers }}</span>
                </div>
            </div>
        </div>

        <!-- Today's Orders Count -->
        <div class="col-md-3">
            <div class="info-box">
                <div class="info-box-icon bg-success">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="info-box-content">
                    <span class="info-box-text">Today's Orders</span>
                    <span class="info-box-number">{{ $todaysOrders }}</span>
                </div>
            </div>
        </div>

        <!-- Today's Total Deposits -->
        <div class="col-md-3">
            <div class="info-box">
                <div class="info-box-icon bg-warning">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="info-box-content">
                    <span class="info-box-text">Today's Total Deposits</span>
                    <span class="info-box-number">${{ number_format($todaysDeposits, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Today's Withdrawals -->
        <div class="col-md-3">
            <div class="info-box">
                <div class="info-box-icon bg-danger">
                    <i class="fas fa-minus-circle"></i>
                </div>
                <div class="info-box-content">
                    <span class="info-box-text">Today's Withdrawals</span>
                    <span class="info-box-number">${{ number_format($todaysWithdrawals, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Latest Users -->
    <div class="card mt-4">
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
                        @foreach($latestUsers as $user)
                            <tr>
                                <td><a href="#">{{ $user->id }}</a></td>
                                <td>{{ $user->name }}</td>
                                <td>
                                    <span class="badge badge-{{ $user->status == 'active' ? 'success' : 'danger' }}">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </td>
                                <td>{{ $user->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<br>
@endsection
