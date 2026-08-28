@php
    $user = $userData['user'];
    $membership = $userData['membership_level'];
    $totalBalance = $userData['total_funds'];
    $todayOrderValue = $userData['today_order_value'];
    $totalTodayOrders = $userData['total_today_orders'];
    $todayCommission = $userData['today_commission'];
    $overpricedAmount = $userData['overpriced_amount'];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Optimization - Segro</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/modern_style.css') }}">
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
        <h1>Data Optimization</h1>
        <div class="header-icon" onclick="toggleSidebar()">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container">
        <!-- Error & Success Messages -->
        @if (session('order_message'))
            <div class="alert alert-error">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <span>{{ session('order_message') }}</span>
            </div>
        @endif
        @if (session('account_message'))
            <div class="alert alert-error">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <octagon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></octagon><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <span style="color: #ef4444;">{{ session('account_message') }}</span>
            </div>
        @endif
        @if (session('order_success_message'))
            <div class="alert alert-success">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                <span>{{ session('order_success_message') }}</span>
            </div>
        @endif
        @if ($overpricedAmount > 0)
            <div class="alert alert-error">
                <span>You're short by <strong>${{ number_format($overpricedAmount, 2) }}</strong> to resume your orders. Please recharge to continue. <a href="/recharge" style="color: inherit; text-decoration: underline; font-weight: 600;">Recharge now</a></span>
            </div>
        @endif

        <!-- Compact wallet strip: quick balance/earnings/orders glance, secondary to the catalog below -->
        <div class="wallet-strip">
            <div class="wallet-strip-item">
                <span class="wallet-strip-value">${{ number_format($totalBalance, 2) }}</span>
                <span class="wallet-strip-label">Balance</span>
            </div>
            <div class="wallet-strip-item">
                <span class="wallet-strip-value">${{ number_format($todayCommission, 2) }}</span>
                <span class="wallet-strip-label">Earnings</span>
            </div>
            <div class="wallet-strip-item">
                <span class="wallet-strip-value">{{ $totalTodayOrders }}<span class="wallet-strip-value-sub">/{{ $membership->order_limit }}</span></span>
                <span class="wallet-strip-label">Orders</span>
            </div>
        </div>

        @php
            $orderListItems = \App\Models\OrderList::limit(15)->get();
        @endphp

        <!-- Catalog hero: this page's actual purpose (browse + generate), now the visual focus -->
        <div class="catalog-hero">
            <span class="section-eyebrow">Marketplace</span>
            <h1 class="catalog-hero-title">Available Listings</h1>
            <p class="catalog-hero-sub">Browse listings currently active for feedback and search optimization, then generate your next assignment.</p>
            <a href="/generate-order" class="btn btn-primary catalog-generate-btn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle><polyline points="12 8 8 12 12 16"></polyline><line x1="16" y1="12" x2="8" y2="12"></line>
                </svg>
                <span>Generate New Order</span>
            </a>
        </div>

        <div class="catalog-meta">{{ count($orderListItems) }} listings available</div>

        <div class="listing-grid">
            @foreach($orderListItems as $item)
                <div class="listing-card">
                    <div class="listing-card-img">
                        <img src="{{ asset('OrderImages/' . $item->image) }}" alt="{{ $item->title }}">
                    </div>
                    <div class="listing-card-body">
                        <span class="listing-card-price">${{ number_format($item->price, 2) }}</span>
                        <h4 class="listing-card-title">{{ $item->title }}</h4>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Bottom Navigation -->
    <div class="bottom-nav">
        <a href="/dashboard" class="nav-item">
            <svg class="nav-icon" viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            <span>Home</span>
        </a>
        <a href="/recharge" class="nav-item">
            <svg class="nav-icon" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            <span>Deposit</span>
        </a>
        <a href="/data-optimization" class="nav-item active">
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
            <a href="/dashboard" class="drawer-item-modern">
                <span class="drawer-item-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg></span>
                <span>Home</span>
            </a>
            <a href="/data-optimization" class="drawer-item-modern active">
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

    <!-- Scripts -->
    <script src="{{ asset('js/modern_scripts.js') }}"></script>
    @include('user.partials.chat-widget')
</body>
</html>
