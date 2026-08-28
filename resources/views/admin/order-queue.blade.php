@extends('admin.layout.master')

@section('title', 'Order Queue')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Order Queue for {{ $user->name }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active">Order Queue</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        @if (session('order_success'))
            <div class="alert alert-success">{{ session('order_success') }}</div>
        @endif
        @if (session('order_error'))
            <div class="alert alert-danger">{{ session('order_error') }}</div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Today's Orders ({{ $orders->count() }})</h3>
                        <div class="card-tools">
                            <a href="{{ route('generate.orders', $user->id) }}"
                                class="btn btn-success btn-sm"
                                onclick="return confirm('Generate this user\'s remaining orders for today?');">
                                Generate Orders
                            </a>
                            <a href="{{ route('reset.todays.orders', $user->id) }}"
                                class="btn btn-warning btn-sm"
                                onclick="return confirm('Are you sure you want to reset today\'s orders?');">
                                Reset Orders
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($orders->isEmpty())
                            <p>No orders generated for this user yet.</p>
                        @else
                            <p class="text-muted">Price and commission can only be edited while an order is still <strong>Pending</strong>. Once an order is completed its commission has already been paid out, so it's locked.</p>
                            <div class="table-responsive p-0">
                                <table class="table table-sm table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Commission</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orders as $index => $order)
                                            <tr data-order-id="{{ $order->id }}">
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $order->orderList->title ?? 'N/A' }}</td>
                                                @if ($order->type === 'Incomplete')
                                                    <td><input type="number" step="0.01" class="form-control form-control-sm edit-price" value="{{ number_format($order->price, 2, '.', '') }}"></td>
                                                    <td><input type="number" step="0.01" class="form-control form-control-sm edit-commission" value="{{ number_format($order->commission, 2, '.', '') }}"></td>
                                                    <td class="total-value">{{ number_format($order->total_amount, 2) }}</td>
                                                    <td><span class="badge badge-warning">Pending</span></td>
                                                    <td><span class="save-status"></span></td>
                                                @else
                                                    <td>{{ number_format($order->price, 2) }}</td>
                                                    <td>{{ number_format($order->commission, 2) }}</td>
                                                    <td>{{ number_format($order->total_amount, 2) }}</td>
                                                    <td><span class="badge badge-success">Completed</span></td>
                                                    <td></td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @csrf

    <script src="{{ asset('js/admin-order-queue.js') }}"></script>
</section>
@endsection
