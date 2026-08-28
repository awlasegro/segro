<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitation - Segro</title>
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
        <h1>Invitation</h1>
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
        <div class="card text-center">
            <div style="margin-bottom: 25px;">
                <img src="{{ asset('images/download (3).png') }}" alt="Invitation Image" style="width: 120px; border-radius: 12px;">
            </div>

            <h3 class="card-title">Share the Experience</h3>
            <p style="font-size: 13px; color: var(--text-secondary); margin-bottom: 20px;">Invite your friends to the elite Segro club and get up to 30% dynamic referral commission.</p>

            <div class="form-group">
                <label>My Invitation Code</label>
                <div class="copy-block">
                    <input type="text" id="invitation-code" class="copy-input" value="{{ $invitationCode }}" readonly>
                    <button class="copy-btn" onclick="copyToClipboard('invitation-code')">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                        </svg>
                    </button>
                </div>
            </div>
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

    <!-- Scripts -->
    <script src="{{ asset('js/modern_scripts.js') }}"></script>
    @include('user.partials.chat-widget')
</body>
</html>
