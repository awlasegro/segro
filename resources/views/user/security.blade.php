<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Center - Segro</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/modern_style.css') }}">
</head>
<body>

    <!-- Header -->
    <header class="app-header">
        <a href="/profile" class="header-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
        </a>
        <h1>Security Center</h1>
        <div class="header-icon" style="opacity: 0;"></div>
    </header>

    <!-- Main Content -->
    <div class="container">
        @if (session('pass_status'))
            <div class="alert alert-success">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                <span>{{ session('pass_status') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                <div>
                    @foreach ($errors->all() as $error)
                        <p style="margin-bottom: 4px;">{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="card">
            <h3 class="card-title">Manage Passwords</h3>
            <p style="font-size: 13px; color: var(--text-secondary); margin-bottom: 20px;">Protect your account and withdrawal transactions with strong passwords.</p>

            <!-- Tab selector -->
            <div class="tabs-bar">
                <button class="tab-btn active" onclick="switchTab('login', this)">Login Password</button>
                <button class="tab-btn" onclick="switchTab('wallet', this)">Wallet Password</button>
            </div>

            <!-- Login Password form -->
            <div id="login" class="tab-content" style="display: block;">
                <form method="POST" action="{{ route('change.login.password') }}">
                    @csrf
                    <div class="form-group">
                        <label for="current-password-login">Current Login Password</label>
                        <input type="password" id="current-password-login" name="current_password" placeholder="••••••••" required>
                    </div>

                    <div class="form-group">
                        <label for="new-password-login">New Login Password</label>
                        <input type="password" id="new-password-login" name="new_password" placeholder="••••••••" required>
                    </div>

                    <div class="form-group" style="margin-bottom: 25px;">
                        <label for="reenter-password-login">Confirm New Login Password</label>
                        <input type="password" id="reenter-password-login" name="new_password_confirmation" placeholder="••••••••" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Login Password</button>
                </form>
            </div>

            <!-- Wallet Password form -->
            <div id="wallet" class="tab-content" style="display: none;">
                <form method="POST" action="{{ route('change.wallet.password') }}">
                    @csrf
                    <div class="form-group">
                        <label for="current-password-wallet">Current Wallet Password</label>
                        <input type="password" id="current-password-wallet" name="current_wallet_password" placeholder="••••••••" required>
                    </div>

                    <div class="form-group">
                        <label for="new-password-wallet">New Wallet Password</label>
                        <input type="password" id="new-password-wallet" name="new_wallet_password" placeholder="••••••••" required>
                    </div>

                    <div class="form-group" style="margin-bottom: 25px;">
                        <label for="reenter-password-wallet">Confirm New Wallet Password</label>
                        <input type="password" id="reenter-password-wallet" name="new_wallet_password_confirmation" placeholder="••••••••" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Wallet Password</button>
                </form>
            </div>
        </div>
    </div>



    <!-- Scripts -->
    <script src="{{ asset('js/modern_scripts.js') }}"></script>
    @include('user.partials.chat-widget')
</body>
</html>
