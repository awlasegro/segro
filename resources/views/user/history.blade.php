<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction History - Segro</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/modern_style.css') }}">
</head>
<body>

    <!-- Header -->
    <header class="app-header">
        <a href="/dashboard" class="header-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
        </a>
        <h1>Order History</h1>
        <div class="header-icon" style="opacity: 0;"></div>
    </header>

    <!-- Main Content -->
    <div class="container">
        @if (session('error'))
            <div class="alert alert-error">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Tab Bar -->
        <div class="tabs-bar">
            <button class="tab-btn active" onclick="switchTab('all-tab', this)">All</button>
            <button class="tab-btn" onclick="switchTab('incomplete-tab', this)">Pending</button>
            <button class="tab-btn" onclick="switchTab('completed-tab', this)">Completed</button>
            <button class="tab-btn" onclick="switchTab('on-hold-tab', this)">On Hold</button>
        </div>

        <!-- TAB CONTENT: ALL -->
        <div id="all-tab" class="tab-content" style="display: block;">
            <!-- Pending Order from oneIncompleteOrder -->
            @if ($oneIncompleteOrder)
                <div class="history-card">
                    <div class="history-card-header">
                        <span class="history-date">{{ $oneIncompleteOrder->created_at->format('Y-m-d') }}</span>
                        <span class="status-badge incomplete">Incomplete</span>
                    </div>
                    <div class="history-card-body">
                        <img src="{{ asset('OrderImages/' . $oneIncompleteOrder->orderList->image) }}" alt="Order Image" class="history-card-img">
                        <div class="history-card-details">
                            <h4>{{ $oneIncompleteOrder->orderList->title }}</h4>
                            <p>Order ID: #{{ $oneIncompleteOrder->id }}</p>
                        </div>
                    </div>
                    <div class="history-card-footer">
                        <div class="price-item">
                            <h5>${{ number_format($oneIncompleteOrderPrice, 2) }}</h5>
                            <p>Price</p>
                        </div>
                        <div class="price-item">
                            <h5 style="color: var(--success-color);">+${{ number_format($oneIncompleteOrderCommission, 2) }}</h5>
                            <p>Profit</p>
                        </div>
                    </div>
                    <div style="margin-top: 15px;">
                        <a href="{{ route('generate.order') }}" class="btn btn-primary" style="padding: 10px 16px; font-size: 13px; width: 100%; display: inline-block; text-align: center; text-decoration: none;">Continue Order</a>
                    </div>
                </div>
            @endif

            <!-- Completed & On Hold Orders -->
            @foreach ([$completedOrders, $onHoldOrders] as $orders)
                @foreach ($orders as $order)
                    <div class="history-card">
                        <div class="history-card-header">
                            <span class="history-date">{{ $order['created_at'] }}</span>
                            <span class="status-badge {{ strtolower($order['status']) }}">{{ $order['status'] }}</span>
                        </div>
                        <div class="history-card-body">
                            <img src="{{ asset('OrderImages/' . $order['image']) }}" alt="Order Image" class="history-card-img">
                            <div class="history-card-details">
                                <h4>{{ $order['title'] }}</h4>
                                <p>Optimized review order</p>
                            </div>
                        </div>
                        <div class="history-card-footer">
                            <div class="price-item">
                                <h5>${{ number_format($order['price'], 2) }}</h5>
                                <p>Price</p>
                            </div>
                            <div class="price-item">
                                <h5 style="color: var(--success-color);">+${{ number_format($order['commission'], 2) }}</h5>
                                <p>Profit</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endforeach

            @if (!$oneIncompleteOrder && $completedOrders->isEmpty() && $onHoldOrders->isEmpty())
                <p class="text-center" style="color: var(--text-muted); padding: 40px 0;">No transaction records found.</p>
            @endif
        </div>

        <!-- TAB CONTENT: INCOMPLETE / PENDING -->
        <div id="incomplete-tab" class="tab-content" style="display: none;">
            @if ($oneIncompleteOrder)
                <div class="history-card">
                    <div class="history-card-header">
                        <span class="history-date">{{ $oneIncompleteOrder->created_at->format('Y-m-d') }}</span>
                        <span class="status-badge incomplete">Incomplete</span>
                    </div>
                    <div class="history-card-body">
                        <img src="{{ asset('OrderImages/' . $oneIncompleteOrder->orderList->image) }}" alt="Order Image" class="history-card-img">
                        <div class="history-card-details">
                            <h4>{{ $oneIncompleteOrder->orderList->title }}</h4>
                            <p>Order ID: #{{ $oneIncompleteOrder->id }}</p>
                        </div>
                    </div>
                    <div class="history-card-footer">
                        <div class="price-item">
                            <h5>${{ number_format($oneIncompleteOrderPrice, 2) }}</h5>
                            <p>Price</p>
                        </div>
                        <div class="price-item">
                            <h5 style="color: var(--success-color);">+${{ number_format($oneIncompleteOrderCommission, 2) }}</h5>
                            <p>Profit</p>
                        </div>
                    </div>
                    <div style="margin-top: 15px;">
                        <a href="{{ route('generate.order') }}" class="btn btn-primary" style="padding: 10px 16px; font-size: 13px; width: 100%; display: inline-block; text-align: center; text-decoration: none;">Continue Order</a>
                    </div>
                </div>
            @else
                <p class="text-center" style="color: var(--text-muted); padding: 40px 0;">No pending transactions.</p>
            @endif
        </div>

        <!-- TAB CONTENT: COMPLETED -->
        <div id="completed-tab" class="tab-content" style="display: none;">
            @if ($completedOrders->isNotEmpty())
                @foreach ($completedOrders as $order)
                    <div class="history-card">
                        <div class="history-card-header">
                            <span class="history-date">{{ $order['created_at'] }}</span>
                            <span class="status-badge complete">Completed</span>
                        </div>
                        <div class="history-card-body">
                            <img src="{{ asset('OrderImages/' . $order['image']) }}" alt="Order Image" class="history-card-img">
                            <div class="history-card-details">
                                <h4>{{ $order['title'] }}</h4>
                                <p>Optimized review task</p>
                            </div>
                        </div>
                        <div class="history-card-footer">
                            <div class="price-item">
                                <h5>${{ number_format($order['price'], 2) }}</h5>
                                <p>Price</p>
                            </div>
                            <div class="price-item">
                                <h5 style="color: var(--success-color);">+${{ number_format($order['commission'], 2) }}</h5>
                                <p>Profit</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-center" style="color: var(--text-muted); padding: 40px 0;">No completed transactions.</p>
            @endif
        </div>

        <!-- TAB CONTENT: ON HOLD -->
        <div id="on-hold-tab" class="tab-content" style="display: none;">
            @if ($onHoldOrders->isNotEmpty())
                @foreach ($onHoldOrders as $order)
                    <div class="history-card">
                        <div class="history-card-header">
                            <span class="history-date">{{ $order['created_at'] }}</span>
                            <span class="status-badge on-hold">On Hold</span>
                        </div>
                        <div class="history-card-body">
                            <img src="{{ asset('OrderImages/' . $order['image']) }}" alt="Order Image" class="history-card-img">
                            <div class="history-card-details">
                                <h4>{{ $order['title'] }}</h4>
                                <p>Transaction status pending hold</p>
                            </div>
                        </div>
                        <div class="history-card-footer">
                            <div class="price-item">
                                <h5>${{ number_format($order['price'], 2) }}</h5>
                                <p>Price</p>
                            </div>
                            <div class="price-item">
                                <h5 style="color: var(--success-color);">+${{ number_format($order['commission'], 2) }}</h5>
                                <p>Profit</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-center" style="color: var(--text-muted); padding: 40px 0;">No on-hold transactions.</p>
            @endif
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/modern_scripts.js') }}"></script>
    @include('user.partials.chat-widget')
</body>
</html>
