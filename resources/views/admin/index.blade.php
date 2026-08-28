@extends('admin.layout.master')

@section('title', 'Users List')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>All Members List</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active">Members</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Alerts -->
        @if (session('funds_success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> {{ session('funds_success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        @if (session('order_success'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> {{ session('order_success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        @if (session('order_error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> {{ session('order_error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        @if (session('upd_success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>User Update!</strong> {{ session('upd_success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        @if (session('add_success'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <strong>Added!</strong> {{ session('add_success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        @if (session('walletUpdateSuccess'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <strong>Wallet Updated!</strong> {{ session('walletUpdateSuccess') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <a href="/add-member" class="btn btn-primary btn-flat">New Member</a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <!-- Search Input -->
                        <div class="mb-3">
                            <form method="GET" action="{{ route('administration') }}" class="d-flex">
                                <!-- Search Input (90% width) -->
                                <input type="text" name="search" id="search-input" class="form-control me-2" placeholder="Search..." value="{{ request()->search }}" style="width: 90%;">

                                <!-- Search Button (10% width) -->
                                <button type="submit" class="btn btn-primary" style="width: 10%;">Search</button>
                            </form>
                        </div>

                        <!-- Table -->
                        <div class="table-responsive p-0">
                            <table class="table table-sm table-striped text-nowrap">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Username</th>
                                        <th>Balance</th>
                                        <th>Available</th>
                                        <th>Total Orders</th>
                                        <th>Reward</th>
                                        <th>%</th>
                                        <th>Referral Code</th>
                                        <th>Membership</th>
                                        <th>Status</th>
                                        <th>W Status</th>
                                        <th>Registration Time</th>
                                        <th>Last Login</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="users-table-body">
                                    @foreach ($users as $item)
                                        <tr>
                                            <td>{{ $item['user']->id }}</td>
                                            <td>{{ $item['user']->name }}</td>
                                            <td>{{ number_format($item['total_funds'], 2) }}</td>
                                            <td>{{ $item['total_order_limit'] }}</td>
                                            <td>{{ $item['processed_orders_count'] }}</td>
                                            <td>{{ number_format($item['daily_commission'], 2) }}</td>
                                            <td>{{ $item['user']->credibility }}</td>
                                            <td>{{ $item['registered_with_code'] ?? 'N/A' }}</td>
                                            <td>{{ $item['membership_level']->level_name }}</td>
                                            <td><span class="badge badge-{{ $item['user']->status == 'active' ? 'success' : 'danger' }}">
                                                {{ ucfirst($item['user']->status) }}
                                            </span></td>
                                            <td><span class="badge badge-{{ $item['user']->wallet_status == 'active' ? 'success' : 'danger' }}">
                                                {{ ucfirst($item['user']->wallet_status) }}
                                            </span></td>
                                            <td>{{ $item['user']->created_at->format('Y-m-d H:i:s') }}</td>
                                            <td>{{ $item['user']->updated_at->format('Y-m-d H:i:s') }}</td>
                                            <td>
                                                <a href="{{ URL('/reset-orders', $item['user']->id) }}" class="btn btn-primary btn-sm">Setup Order</a>
                                                <a href="{{ URL('/add-debit', $item['user']->id) }}" class="btn btn-danger btn-sm">Add Debit</a> <br>
                                                <a href="{{ route('reset.todays.orders', $item['user']->id) }}"
                                                    class="btn btn-warning btn-sm"
                                                    onclick="return confirm('Are you sure you want to reset today\'s orders?');">
                                                    Reset Orders
                                                </a>
                                                <a href="{{ route('generate.orders', $item['user']->id) }}"
                                                    class="btn btn-success btn-sm"
                                                    onclick="return confirm('Generate this user\'s remaining orders for today?');">
                                                    Generate Orders
                                                </a>
                                                <a href="{{ route('order.queue', $item['user']->id) }}" class="btn btn-secondary btn-sm">View Queue</a>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        More Actions
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="{{ URL('/update-user', $item['user']->id) }}">Edit</a>
                                                        <a class="dropdown-item" href="{{ URL('/vallet-information', $item['user']->id) }}">Wallet Information</a>
                                                        <a class="dropdown-item" href="{{ URL('/user-recharge-history', $item['user']->id) }}">Recharge History</a>
                                                        <a class="dropdown-item" href="{{ URL('/user-redemption-history', $item['user']->id) }}">Redemtion History</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>Username</th>
                                        <th>Balance</th>
                                        <th>Available</th>
                                        <th>Total Orders</th>
                                        <th>Reward</th>
                                        <th>%</th>
                                        <th>Referral Code</th>
                                        <th>Membership</th>
                                        <th>Status</th>
                                        <th>W Status</th>
                                        <th>Registration Time</th>
                                        <th>Last Login</th>
                                        <th>Actions</th>
                                    </tr>
                                </tfoot>
                            </table>
                            <!-- Pagination Links -->
                            <div class="d-flex justify-content-center">
                                {{ $pagination->links('pagination::bootstrap-5') }}
                            </div>

                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#search-input').on('keyup', function() {
            var searchTerm = $(this).val().toLowerCase();
            $('#users-table-body tr').each(function() {
                var rowText = $(this).text().toLowerCase();
                // Hide row if search term is not found
                $(this).toggle(rowText.indexOf(searchTerm) > -1);
            });
        });
    });
</script>
@endpush
@endsection
