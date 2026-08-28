@php
    $wallet = \App\Models\UserVallet::where('user_id', Auth::id())->first();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Withdraw - Segro</title>
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
        <h1>Wallet</h1>
        <div class="header-icon" onclick="toggleSidebar()">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </div>
    </header>

    <div class="container">
        <!-- Error & Success Messages -->
        @if ($errors->any())
            <div class="alert alert-error">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <ul style="margin: 0; padding-left: 20px; color: #ef4444; font-size: 13px; list-style-type: disc;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if ($overpricedAmount > 0)
            <div class="alert alert-error">
                <span>You're short by <strong>${{ number_format($overpricedAmount, 2) }}</strong> to resume your orders. Please recharge to continue. <a href="/recharge" style="color: inherit; text-decoration: underline; font-weight: 600;">Recharge now</a></span>
            </div>
        @endif

        <!-- Balance Info Card -->
        <div class="card" style="background-color: var(--accent-color); border-color: var(--accent-color); color: #fff;">
            <p style="font-size: 11px; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; opacity: 0.9; margin-bottom: 4px;">Available Balance</p>
            <h2 style="font-size: 24px; font-weight: 700;">$<span id="display-balance">{{ number_format($adjustedTotalFunds, 2) }}</span></h2>
            <p style="font-size: 11px; opacity: 0.85; margin-top: 10px;">Kindly verify with our Live Support once submitted. Processed within an hour.</p>
        </div>

        <!-- Tab Selector -->
        <div class="tabs-bar">
            <button class="tab-btn" onclick="window.location.href='/recharge'">Deposit</button>
            <button class="tab-btn active">Withdraw</button>
            <button class="tab-btn" onclick="window.location.href='/recharge-history'">History</button>
        </div>

        <!-- Withdrawal Form Card -->
        <div class="card">
            <h3 class="card-title">Request withdrawal</h3>

            @if($wallet)
                <!-- Form -->
                <form action="{{ route('redeem') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Select Preset Amount</label>
                        <div class="preset-chips">
                            <div class="preset-chip" onclick="selectPresetAmount(50, this)">50</div>
                            <div class="preset-chip" onclick="selectPresetAmount(100, this)">100</div>
                            <div class="preset-chip" onclick="selectPresetAmount(500, this)">500</div>
                            <div class="preset-chip" onclick="selectPresetAmount({{ $adjustedTotalFunds }}, this)">All</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="amount">Amount (USDT)</label>
                        <input type="number" step="0.01" id="amount" name="amount" placeholder="0.00" min="0.01" max="{{ $adjustedTotalFunds }}" required>
                        <p style="font-size: 11px; color: var(--text-muted); margin-top: 4px;">Available balance: $<span id="label-balance">{{ number_format($adjustedTotalFunds, 2) }}</span></p>
                    </div>

                    <div class="form-group" style="margin-bottom: 25px;">
                        <label for="password">Withdrawal password</label>
                        <input type="password" id="password" name="password" placeholder="••••••••" required>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; margin-bottom: 10px;">Request Withdrawal</button>
                </form>

                <!-- Payout wallet bound card (Shown Below) -->
                <div class="payout-card" style="margin-top: 20px;">
                    <div class="payout-card-header">
                        <div class="payout-card-logo">
                            <div class="payout-card-logo-icon">₮</div>
                            <div class="payout-card-logo-text">Receiving Wallet</div>
                        </div>
                        <div style="display: flex; gap: 8px;">
                            <span class="payout-card-network-badge">{{ $wallet->type }}</span>
                            @if($wallet->blockchain)
                                <span class="payout-card-network-badge" style="background: rgba(16, 185, 129, 0.15); color: var(--success-color); border-color: rgba(16, 185, 129, 0.3);">{{ $wallet->blockchain }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="payout-card-address" style="font-size: 16px;">{{ $wallet->vallet_address }}</div>
                    <div class="payout-card-footer">
                        <div class="payout-card-verified">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            Linked Payout Account
                        </div>
                    </div>
                </div>
            @else
                <!-- No wallet message -->
                <div class="text-center" style="padding: 20px 0;">
                    <p style="font-size: 14px; color: var(--text-secondary); margin-bottom: 20px;">You need to link a payout wallet address first before you can request withdrawals.</p>
                    <a href="/wallet-information" class="btn btn-primary">Link Wallet Address</a>
                </div>
            @endif
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
        <a href="/data-optimization" class="nav-item">
            <svg class="nav-icon" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <span>Orders</span>
        </a>
        <a href="/redemption" class="nav-item active">
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
            <a href="/data-optimization" class="drawer-item-modern">
                <span class="drawer-item-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg></span>
                <span>View Orders</span>
            </a>
            <a href="/recharge" class="drawer-item-modern">
                <span class="drawer-item-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg></span>
                <span>Deposit</span>
            </a>
            <a href="/redemption" class="drawer-item-modern active">
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
    <script src="{{ asset('js/redemption.js') }}"></script>
    @include('user.partials.chat-widget')
</body>
</html>
