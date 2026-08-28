<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deposit History - Segro</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/modern_style.css') }}">
</head>
<body>

    <!-- Header -->
    <header class="app-header">
        <a href="/recharge" class="header-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
        </a>
        <h1>Deposit History</h1>
        <div class="header-icon" style="opacity: 0;"></div>
    </header>

    <!-- Main Content -->
    <div class="container">
        <!-- History Sub-Tabs -->
        <div class="tabs-bar" style="margin-bottom: 20px;">
            <button class="tab-btn active" onclick="window.location.href='/recharge-history'">Recharge History</button>
            <button class="tab-btn" onclick="window.location.href='/redemption-history'">Redemption History</button>
        </div>

        <div class="card">
            <h3 class="card-title">Recent Deposits</h3>
            
            <div style="display: flex; flex-direction: column; gap: 12px;">
                @forelse($rechargeHistory->where('status', 'active') as $recharge)
                    <div style="background-color: var(--bg-tertiary); border: 1px solid var(--border-color); border-radius: 10px; padding: 14px 16px; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <span style="font-size: 11px; color: var(--text-muted); display: block; margin-bottom: 2px;">{{ $recharge->created_at->format('Y-m-d H:i') }}</span>
                            <span style="font-size: 14px; font-weight: 700; color: var(--text-primary);">+${{ number_format($recharge->amount, 2) }}</span>
                        </div>
                        <span class="status-badge complete">Success</span>
                    </div>
                @empty
                    <p class="text-center" style="color: var(--text-muted); padding: 20px 0; font-size: 13px;">No deposit records found.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/modern_scripts.js') }}"></script>
    @include('user.partials.chat-widget')
</body>
</html>
