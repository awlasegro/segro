<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Segro</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/modern_style.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick-theme.min.css"/>
    <script src="https://code.jquery.com/jquery-2.2.0.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.js"></script>
</head>
<body>

    <!-- Header -->
    <header class="app-header">
        <div class="header-icon-group">
            <a href="/profile" class="header-icon" title="Profile">
                <svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </a>
            <a href="/support" class="header-icon" title="Support">
                <svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 18v-6a9 9 0 0118 0v6M21 19a2 2 0 01-2 2h-1a2 2 0 01-2-2v-3a2 2 0 012-2h3M3 19a2 2 0 002 2h1a2 2 0 002-2v-3a2 2 0 00-2-2H3"/></svg>
            </a>
        </div>
        <div class="header-brand"><img src="{{ asset('images/logo.png') }}" alt="SEGRO" class="header-brand-logo"></div>
        <div class="header-icon" onclick="toggleSidebar()">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </div>
    </header>

    <!-- Main Container -->
    <div class="container">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        @if ($overpricedAmount > 0)
            <div class="alert alert-error">
                <span>You're short by <strong>${{ number_format($overpricedAmount, 2) }}</strong> to resume your orders. Please recharge to continue. <a href="/recharge" style="color: inherit; text-decoration: underline; font-weight: 600;">Recharge now</a></span>
            </div>
        @endif

        <!-- Unified Overview: balance + 24h metrics + earnings chart, one cohesive hero card -->
        <div class="hero-balance-card">
            <span class="hero-balance-eyebrow">Welcome back, {{ $user->name }}</span>
            <span class="hero-balance-label">Account Balance</span>
            <div class="hero-balance-value">${{ number_format($totalBalance, 2) }}</div>

            <div class="hero-balance-strip">
                <div class="hero-balance-metric">
                    <span class="hero-balance-metric-value">${{ number_format($totalRevenue, 2) }}</span>
                    <span class="hero-balance-metric-label">Total revenue</span>
                </div>
                <div class="hero-balance-metric">
                    <span class="hero-balance-metric-value">${{ number_format($todayCommission, 2) }}</span>
                    <span class="hero-balance-metric-label">Commission (24h)</span>
                </div>
                <div class="hero-balance-metric">
                    <span class="hero-balance-metric-value">{{ $completedOrdersCount }}<span class="hero-balance-metric-value-sub">/{{ $orderLimit }}</span></span>
                    <span class="hero-balance-metric-label">Orders (24h)</span>
                </div>
            </div>

            <div class="hero-chart-section">
                <div class="d-flex justify-between align-center mb-10">
                    <h3 class="card-title" style="margin: 0;">Earnings overview</h3>
                    <div style="display: flex; gap: 6px;">
                        <span class="preset-chip chart-range-chip active" id="chip-7d" onclick="setEarningsChartRange('7d', this)">7D</span>
                        <span class="preset-chip chart-range-chip" id="chip-30d" onclick="setEarningsChartRange('30d', this)">30D</span>
                    </div>
                </div>

                <!-- Chart Visualization -->
                <div class="chart-container">
                    <svg class="chart-svg" viewBox="0 0 100 40" preserveAspectRatio="none">
                        <defs>
                            <linearGradient id="chart-grad" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="var(--accent-color)" stop-opacity="0.4"/>
                                <stop offset="100%" stop-color="var(--accent-color)" stop-opacity="0.0"/>
                            </linearGradient>
                        </defs>
                        <path id="earnings-fill-path" d="{{ $earningsChart7d['fill'] }}" fill="url(#chart-grad)" />
                        <path id="earnings-line-path" d="{{ $earningsChart7d['line'] }}" fill="none" stroke="var(--accent-color)" stroke-width="1.5" />
                    </svg>
                </div>

                <!-- Period summary: amount earned + tasks completed for the selected range -->
                <div class="card-feature-footer">
                    <div>
                        <span class="feature-stat-label">Earned</span>
                        <span id="chart-total-earned" class="feature-stat-value">${{ number_format($earningsTotal7d, 2) }}</span>
                    </div>
                    <div>
                        <span class="feature-stat-label">Tasks Completed</span>
                        <span id="chart-tasks-completed" class="feature-stat-value">{{ $tasksTotal7d }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions: single 4-column icon dock, every action equal weight -->
        <div class="mb-20">
            <h3 class="section-heading">Quick actions</h3>

            <div class="quick-dock">
                <a href="/data-optimization" class="quick-dock-item">
                    <span class="quick-dock-icon"><svg viewBox="0 0 24 24"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg></span>
                    <span class="quick-dock-label">Orders</span>
                </a>
                <a href="/recharge" class="quick-dock-item">
                    <span class="quick-dock-icon"><svg viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg></span>
                    <span class="quick-dock-label">Deposit</span>
                </a>
                <a href="/redemption" class="quick-dock-item">
                    <span class="quick-dock-icon"><svg viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg></span>
                    <span class="quick-dock-label">Withdraw</span>
                </a>
                <a href="/support" class="quick-dock-item">
                    <span class="quick-dock-icon"><svg viewBox="0 0 24 24"><path d="M3 18v-6a9 9 0 0118 0v6M21 19a2 2 0 01-2 2h-1a2 2 0 01-2-2v-3a2 2 0 012-2h3M3 19a2 2 0 002 2h1a2 2 0 002-2v-3a2 2 0 00-2-2H3"/></svg></span>
                    <span class="quick-dock-label">Support</span>
                </a>
                <a href="/profile" class="quick-dock-item">
                    <span class="quick-dock-icon"><svg viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></span>
                    <span class="quick-dock-label">Profile</span>
                </a>
                <a href="/faq" class="quick-dock-item">
                    <span class="quick-dock-icon"><svg viewBox="0 0 24 24"><path d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></span>
                    <span class="quick-dock-label">FAQ</span>
                </a>
                <a href="/company-information" class="quick-dock-item">
                    <span class="quick-dock-icon"><svg viewBox="0 0 24 24"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></span>
                    <span class="quick-dock-label">About Us</span>
                </a>
                <a href="/termsconditions" class="quick-dock-item">
                    <span class="quick-dock-icon"><svg viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></span>
                    <span class="quick-dock-label">Terms</span>
                </a>
            </div>
        </div>

        <!-- Featured Listings: preview strip linking through to the full marketplace -->
        <div class="mb-20">
            <div class="d-flex justify-between align-center mb-12">
                <div>
                    <span class="section-eyebrow">Marketplace</span>
                    <h3 class="section-heading" style="margin-top: 0;">Featured Listings</h3>
                </div>
                <a href="/data-optimization" style="font-size: 12px; font-weight: 700; color: var(--accent-color);">View all</a>
            </div>
            <section class="customer-logos slider">
                @foreach($orderListItems as $item)
                    <div class="slide">
                        <div class="product-card">
                            <div class="product-card-img">
                                <img src="{{ asset('OrderImages/' . $item->image) }}" alt="{{ $item->title }}">
                            </div>
                            <div class="product-card-body">
                                <span class="product-card-price">${{ number_format($item->price, 2) }}</span>
                                <h4 class="product-card-title">{{ $item->title }}</h4>
                            </div>
                        </div>
                    </div>
                @endforeach
            </section>
        </div>
    </div>

    <!-- Bottom Navigation -->
    <div class="bottom-nav">
        <a href="/dashboard" class="nav-item active">
            <svg class="nav-icon" viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            <span>Home</span>
        </a>
        <a href="/recharge" class="nav-item">
            <svg class="nav-icon" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            <span>Deposit</span>
        </a>
        <a href="/data-optimization" class="nav-item">
            <svg class="nav-icon" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <span>Orders</span>
        </a>
        <a href="/redemption" class="nav-item">
            <svg class="nav-icon" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
            <span>Withdraw</span>
        </a>
        <a href="/support" class="nav-item">
            <svg class="nav-icon" viewBox="0 0 24 24"><path d="M3 18v-6a9 9 0 0118 0v6M21 19a2 2 0 01-2 2h-1a2 2 0 01-2-2v-3a2 2 0 012-2h3M3 19a2 2 0 002 2h1a2 2 0 002-2v-3a2 2 0 00-2-2H3"/></svg>
            <span>Support</span>
        </a>
    </div>

    <!-- Drawer Overlay -->
    <div id="sidebar-overlay" class="sidebar-overlay" onclick="toggleSidebar()"></div>

    <!-- Right Sidebar Drawer -->
    <div id="sidebar-drawer" class="sidebar-drawer">
        <div class="drawer-header-modern">
            <div class="drawer-header-user">
                <div class="drawer-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                <div class="drawer-brand">
                    <span class="drawer-brand-name">{{ Auth::user()->name }}</span>
                    <span class="drawer-brand-sub"><img src="{{ asset('images/logo.png') }}" alt="SEGRO" class="drawer-brand-sub-logo"> Member</span>
                </div>
            </div>
            <div class="drawer-close" onclick="toggleSidebar()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </div>
        </div>
        <nav class="drawer-menu-modern">
            <div class="drawer-menu-label">Menu</div>
            <a href="/dashboard" class="drawer-item-modern active">
                <span class="drawer-item-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg></span>
                <span>Home</span>
            </a>
            <a href="/data-optimization" class="drawer-item-modern">
                <span class="drawer-item-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg></span>
                <span>View Orders</span>
            </a>
            <a href="/recharge" class="drawer-item-modern">
                <span class="drawer-item-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg></span>
                <span>Deposit</span>
            </a>
            <a href="/redemption" class="drawer-item-modern">
                <span class="drawer-item-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg></span>
                <span>Withdraw</span>
            </a>
            <a href="/support" class="drawer-item-modern">
                <span class="drawer-item-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 18v-6a9 9 0 0118 0v6M21 19a2 2 0 01-2 2h-1a2 2 0 01-2-2v-3a2 2 0 012-2h3M3 19a2 2 0 002 2h1a2 2 0 002-2v-3a2 2 0 00-2-2H3"/></svg></span>
                <span>Support</span>
            </a>
            <div class="drawer-menu-label">Account</div>
            <a href="/profile" class="drawer-item-modern">
                <span class="drawer-item-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></span>
                <span>Profile</span>
            </a>
            <a href="/faq" class="drawer-item-modern">
                <span class="drawer-item-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></span>
                <span>FAQ</span>
            </a>
            <a href="/company-information" class="drawer-item-modern">
                <span class="drawer-item-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></span>
                <span>About Us</span>
            </a>
            <a href="/termsconditions" class="drawer-item-modern">
                <span class="drawer-item-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></span>
                <span>Terms</span>
            </a>
        </nav>
        <div class="drawer-footer">
            <a href="/user.logout" class="btn btn-danger">Logout</a>
        </div>
    </div>

    <!-- Chart data (consumed by dashboard.js) -->
    <script type="application/json" id="earnings-chart-data">
        {
            "7d": {
                "line": @json($earningsChart7d['line']),
                "fill": @json($earningsChart7d['fill']),
                "totalEarned": @json(number_format($earningsTotal7d, 2)),
                "tasksCompleted": @json($tasksTotal7d)
            },
            "30d": {
                "line": @json($earningsChart30d['line']),
                "fill": @json($earningsChart30d['fill']),
                "totalEarned": @json(number_format($earningsTotal30d, 2)),
                "tasksCompleted": @json($tasksTotal30d)
            }
        }
    </script>

    <!-- Scripts -->
    <script src="{{ asset('js/modern_scripts.js') }}"></script>
    <script src="{{ asset('js/dashboard.js') }}"></script>
    @include('user.partials.chat-widget')
</body>
</html>
