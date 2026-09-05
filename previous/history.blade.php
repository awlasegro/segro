<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Records</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: #f8f9fa;
            color: #333333;
            padding-bottom: 120px; /* extra space for fixed footer */
        }

        :root { --header-top: 72px; } /* safe default header height for sticky offset */

        /* Header */
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px;
            background: #ffffff;
            border-bottom: 1px solid #e0e0e0;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .logo {
            font-size: 18px;
            font-weight: 700;
            color: #333333;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .user-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            cursor: pointer;
        }

        /* Tabs */
        .tabs-container {
            background: #ffffff;
            padding: 0;
            border-bottom: 2px solid #f0f0f0;
            display: flex;
            overflow-x: auto;
            gap: 0;
            position: sticky;
            top: var(--header-top); /* offset by dynamic header height */
            margin-top: var(--header-top); /* ensure initial position below header */
            z-index: 70; /* above header so it never gets hidden */
            align-items: center;
        }

        .tab-button {
            flex: 1;
            min-width: 80px;
            padding: 14px 16px;
            border: none;
            background: transparent;
            cursor: pointer;
            color: #999999;
            font-size: 14px;
            font-weight: 600;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
            white-space: nowrap;
            text-align: center;
        }

        .tab-button.active {
            color: #6e6bff;
            border-bottom-color: #6e6bff;
        }

        .tab-button:hover:not(.active) {
            color: #666666;
        }

        .tab-button:active {
            background: #f5f7fc;
        }

        /* Content Area */
        .content-area {
            padding: 16px;
            max-width: 100%;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* Order Card */
        .order-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 14px 18px;
            margin-bottom: 20px;
            box-shadow: 0 12px 40px rgba(16,24,40,0.06);
            display: flex;
            gap: 16px;
            align-items: flex-start;
            position: relative;
            border: 1px solid rgba(110,107,255,0.06);
        }

        .order-card:hover {
            box-shadow: 0 18px 50px rgba(16,24,40,0.08);
            transform: translateY(-1px);
        }

        /* timestamp sits above the card */
        .order-timestamp {
            font-size: 13px;
            color: #9aa0a6;
            margin: 6px 6px 10px 6px;
            display: block;
        }

        /* header right column (stars + badge) */
        .order-header .right-block{display:flex;flex-direction:column;align-items:flex-end;gap:8px}
        .order-header .title-block{min-width:0}

        .order-rating{display:flex;gap:6px;align-items:center}
        .order-rating i{color:#ffc107;font-size:14px}

        /* status badge moved to top-right */
        .status-badge{position:absolute;top:14px;right:14px;display:inline-block;padding:6px 10px;border-radius:12px;font-size:12px;font-weight:700;background:#6e6bff;color:#fff;box-shadow:0 8px 24px rgba(110,107,255,0.12)}
        .status-badge.pending{background:#ffc107;color:#2b2b2b;box-shadow:none}
        .status-badge.undone{background:#f1f1f5;color:#6b6b74}
        .status-badge.completed{background:#22c55e;color:#fff;box-shadow:none}

        .stat-value{font-size:14px;font-weight:700;color:#6e6bff}

        /* small visual divider similar to screenshot */
        .order-card .card-divider{height:1px;background:#f4f5f9;border-radius:2px;margin:12px 0}



        .order-image {
            width: 80px;
            height: 80px;
            border-radius: 8px;
            object-fit: cover;
            background: #f5f7fc;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            flex-shrink: 0;
        }

        .order-content {
            flex: 1;
        }

        .order-title {
            font-size: 15px;
            font-weight: 600;
            color: #333333;
            margin-bottom: 8px;
            line-height: 1.4;
        }

        .order-amount {
            font-size: 14px;
            color: #666666;
            margin-bottom: 8px;
        }

        .order-rating {
            display: flex;
            gap: 2px;
            margin-bottom: 0;
        }

        .order-rating i {
            color: #ffc107;
            font-size: 14px;
        }

        .order-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 12px;
        }

        .stat-item {
            display: flex;
            flex-direction: column;
        }

        .stat-label {
            font-size: 12px;
            color: #999999;
            margin-bottom: 4px;
        }

        .stat-value {
            font-size: 14px;
            font-weight: 700;
            color: #6e6bff;
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
            position: relative;
        }

        /* status-badge styles updated above (see .status-badge definition) */

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 16px;
            color: #999999;
        }

        .empty-state-icon {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        /* Bottom Navigation styles moved to footer partial to avoid duplication */

        /* Responsive */
        @media (max-width: 480px) {
            .order-card {
                flex-direction: column;
                gap: 12px;
            }

            .order-timestamp {
                position: relative;
                top: auto;
                right: auto;
                text-align: left;
                margin-bottom: 8px;
            }

            .order-image {
                width: 60px;
                height: 60px;
                font-size: 28px;
            }

            .order-title {
                font-size: 13px;
            }

            .order-stats {
                grid-template-columns: 1fr 1fr;
                gap: 12px;
            }

            .tab-button {
                padding: 12px;
                font-size: 12px;
            }
        }

        .submit-btn {
            background: #6e6bff;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s ease;
        }

        .submit-btn:hover {
            background: #5a5ac7;
        }

        /* Bootstrap-like button styles */
        .btn {
            display: inline-block;
            font-weight: 400;
            color: #212529;
            text-align: center;
            vertical-align: middle;
            cursor: pointer;
            background-color: transparent;
            border: 1px solid transparent;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: 0.25rem;
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .btn-primary {
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            color: #fff;
            background-color: #0069d9;
            border-color: #0062cc;
        }

        .btn-block {
            display: block;
            width: 100%;
        }

        .btn-lg {
            padding: 0.5rem 1rem;
            font-size: 1.25rem;
            line-height: 1.5;
            border-radius: 0.3rem;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header-top">
        <div class="logo">
            <a href="/dashboard">
                <img src="{{ asset('assets/images/Logo-black.png') }}" alt="Logo" style="height:24px;" />
            </a>
        </div>
        <div class="user-icon">
            <i class="fas fa-user"></i>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="tabs-container">
        <button class="tab-button active" onclick="switchTab('all')">All</button>
        <button class="tab-button" onclick="switchTab('pending')">Pending</button>
        <button class="tab-button" onclick="switchTab('completed')">Completed</button>
        <button class="tab-button" onclick="switchTab('undone')">Undone</button>
    </div>

    <!-- Content Area -->
    <div class="content-area">
        <!-- All Tab -->
        <div id="all" class="tab-content active">
            @if ($oneIncompleteOrder || $completedOrders->isNotEmpty() || $onHoldOrders->isNotEmpty())
                @if ($oneIncompleteOrder)
                    <div class="order-timestamp">{{ $oneIncompleteOrder->created_at->format('Y-m-d H:i:s') }}</div>
                    <div class="order-card">
                        <div class="order-image">
                            @if(isset($oneIncompleteOrder->orderList->image))
                                <img src="{{ asset('OrderImages/' . $oneIncompleteOrder->orderList->image) }}" alt="Order" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <img src="{{ asset('assets/images/123.webp') }}" alt="Order" style="width: 100%; height: 100%; object-fit: cover;">
                            @endif
                        </div>
                        <div class="order-content">
                            <div class="order-header">
                                <div class="title-block">
                                    <div class="order-title">{{ $oneIncompleteOrder->orderList->title ?? 'Order' }}</div>
                                    <div class="order-amount">VIEWS {{ number_format($oneIncompleteOrder->orderList->price ?? 0, 2) }}</div>
                                </div>
                                <div class="right-block">
                                    <div class="order-rating">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <span class="status-badge pending">Pending</span>
                                </div>
                            </div>
                            <div class="order-stats">
                                <div class="stat-item">
                                    <span class="stat-label">Total Amount</span>
                                    <span class="stat-value">VIEWS {{ number_format($oneIncompleteOrder->orderList->price ?? 0, 2) }}</span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-label">Commission</span>
                                    @php
                                        $commissionRate = in_array($oneIncompleteOrder->orderList->id, $selectedOrderIds ?? []) ? 0.30 : ($membership->commission / 100);
                                        $commission = ($oneIncompleteOrder->orderList->price ?? 0) * $commissionRate;
                                    @endphp
                                    <span class="stat-value">VIEWS {{ number_format($commission, 2) }}</span>
                                </div>
                            </div>

                            <!-- Submit action for pending task -->
                            <div class="mt-3">
                                <form method="POST" action="{{ ($oneIncompleteOrder->orderList->price > $funds) ? route('support') : route('submit.order') }}">
                                    @csrf
                                    <input type="hidden" name="order_id" value="{{ $oneIncompleteOrder->order_id }}" />
                                    <input type="hidden" name="commission" value="{{ $commission }}" />
                                    <button type="submit" class="btn btn-primary btn-block btn-lg">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif

                @foreach ($completedOrders as $order)
                    <div class="order-card">
                        <div class="order-timestamp">{{ $order['created_at'] }}</div>
                        <div class="order-image">
                            @if(isset($order['image']))
                                <img src="{{ asset('OrderImages/' . $order['image']) }}" alt="Order" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                ðŸ“¦
                            @endif
                        </div>
                        <div class="order-content">
                            <div class="order-header">
                                <div>
                                    <div class="order-title">{{ $order['title'] ?? 'Order' }}</div>
                                    <div class="order-amount">VIEWS {{ number_format($order['price'] ?? 0, 2) }}</div>
                                    <div class="order-rating">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                </div>
                                <span class="status-badge completed">Completed</span>
                            </div>
                            <div class="order-stats">
                                <div class="stat-item">
                                    <span class="stat-label">Total Amount</span>
                                    <span class="stat-value">VIEWS {{ number_format($order['price'] ?? 0, 2) }}</span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-label">Commission</span>
                                    <span class="stat-value">VIEWS {{ number_format($order['commission'] ?? 0, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                @foreach ($onHoldOrders as $order)
                    <div class="order-card">
                        <div class="order-timestamp">{{ $order['created_at'] }}</div>
                        <div class="order-image">
                            @if(isset($order['image']))
                                <img src="{{ asset('OrderImages/' . $order['image']) }}" alt="Order" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                ðŸ“¦
                            @endif
                        </div>
                        <div class="order-content">
                            <div class="order-header">
                                <div>
                                    <div class="order-title">{{ $order['title'] ?? 'Order' }}</div>
                                    <div class="order-amount">VIEWS {{ number_format($order['price'] ?? 0, 2) }}</div>
                                    <div class="order-rating">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                </div>
                                <span class="status-badge undone">Undone</span>
                            </div>
                            <div class="order-stats">
                                <div class="stat-item">
                                    <span class="stat-label">Total Amount</span>
                                    <span class="stat-value">VIEWS {{ number_format($order['price'] ?? 0, 2) }}</span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-label">Commission</span>
                                    <span class="stat-value">VIEWS {{ number_format($order['commission'] ?? 0, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">ðŸ“‹</div>
                    <p>No orders yet</p>
                </div>
            @endif
        </div>

        <!-- Pending Tab -->
        <div id="pending" class="tab-content">
            @if ($oneIncompleteOrder)
                <div class="order-timestamp">{{ $oneIncompleteOrder->created_at->format('Y-m-d H:i:s') }}</div>
                <div class="order-card">
                    <div class="order-image">
                        @if(isset($oneIncompleteOrder->orderList->image))
                            <img src="{{ asset('OrderImages/' . $oneIncompleteOrder->orderList->image) }}" alt="Order" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            ðŸ“¦
                        @endif
                    </div>
                    <div class="order-content">
                        <div class="order-header">
                            <div class="title-block">
                                <div class="order-title">{{ $oneIncompleteOrder->orderList->title ?? 'Order' }}</div>
                                <div class="order-amount">VIEWS {{ number_format($oneIncompleteOrder->orderList->price ?? 0, 2) }}</div>
                            </div>
                            <div class="right-block">
                                <div class="order-rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <span class="status-badge pending">Pending</span>
                            </div>
                        </div>
                        <div class="order-stats">
                            <div class="stat-item">
                                <span class="stat-label">Total Amount</span>
                                <span class="stat-value">VIEWS {{ number_format($oneIncompleteOrder->orderList->price ?? 0, 2) }}</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Commission</span>
                                @php
                                    $commissionRate = in_array($oneIncompleteOrder->orderList->id, $selectedOrderIds ?? []) ? 0.30 : ($membership->commission / 100);
                                    $commission = ($oneIncompleteOrder->orderList->price ?? 0) * $commissionRate;
                                @endphp
                                <span class="stat-value">VIEWS {{ number_format($commission, 2) }}</span>
                            </div>
                        </div>

                        <!-- submit button for pending order -->
                        <div class="mt-3">
                            <form method="POST" action="{{ ($oneIncompleteOrder->orderList->price > $funds) ? route('support') : route('submit.order') }}">
                                @csrf
                                <input type="hidden" name="order_id" value="{{ $oneIncompleteOrder->id }}" />
                                <input type="hidden" name="commission" value="{{ $commission }}" />
                                <button type="submit" class="btn btn-primary btn-block btn-lg">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">ðŸ“‹</div>
                    <p>No pending orders</p>
                </div>
            @endif
        </div>

        <!-- Completed Tab -->
        <div id="completed" class="tab-content">
            @if ($completedOrders->isNotEmpty())
                @foreach ($completedOrders as $order)
                    <div class="order-timestamp">{{ $order['created_at'] }}</div>
                    <div class="order-card">
                        <div class="order-image">
                            @if(isset($order['image']))
                                <img src="{{ asset('OrderImages/' . $order['image']) }}" alt="Order" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                ðŸ“¦
                            @endif
                        </div>
                        <div class="order-content">
                            <div class="order-header">
                                <div class="title-block">
                                    <div class="order-title">{{ $order['title'] ?? 'Order' }}</div>
                                    <div class="order-amount">VIEWS {{ number_format($order['price'] ?? 0, 2) }}</div>
                                </div>
                                <div class="right-block">
                                    <div class="order-rating">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <span class="status-badge completed">Completed</span>
                                </div>
                            </div>
                            <div class="order-stats">
                                <div class="stat-item">
                                    <span class="stat-label">Total Amount</span>
                                    <span class="stat-value">VIEWS {{ number_format($order['price'] ?? 0, 2) }}</span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-label">Commission</span>
                                    <span class="stat-value">VIEWS {{ number_format($order['commission'] ?? 0, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">ðŸ“‹</div>
                    <p>No completed orders</p>
                </div>
            @endif
        </div>

        <!-- Undone Tab -->
        <div id="undone" class="tab-content">
            @if ($onHoldOrders->isNotEmpty())
                @foreach ($onHoldOrders as $order)
                    <div class="order-timestamp">{{ $order['created_at'] }}</div>
                    <div class="order-card">
                        <div class="order-image">
                            @if(isset($order['image']))
                                <img src="{{ asset('OrderImages/' . $order['image']) }}" alt="Order" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                ðŸ“¦
                            @endif
                        </div>
                        <div class="order-content">
                            <div class="order-header">
                                <div class="title-block">
                                    <div class="order-title">{{ $order['title'] ?? 'Order' }}</div>
                                    <div class="order-amount">VIEWS {{ number_format($order['price'] ?? 0, 2) }}</div>
                                </div>
                                <div class="right-block">
                                    <div class="order-rating">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <span class="status-badge undone">Undone</span>
                                </div>
                            </div>
                            <div class="order-stats">
                                <div class="stat-item">
                                    <span class="stat-label">Total Amount</span>
                                    <span class="stat-value">VIEWS {{ number_format($order['price'] ?? 0, 2) }}</span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-label">Commission</span>
                                    <span class="stat-value">VIEWS {{ number_format($order['commission'] ?? 0, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">ðŸ“‹</div>
                    <p>No undone orders</p>
                </div>
            @endif
        </div>
    </div>

    <script>
        function switchTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });

            // Remove active from all buttons
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('active');
            });

            // Show selected tab
            document.getElementById(tabName).classList.add('active');

            // Add active to clicked button
            event.target.classList.add('active');
        }
    </script>
</body>
</html>
