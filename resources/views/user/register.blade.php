<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - Segro</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/modern_style.css') }}">
</head>
<body>
    <div class="gradient-mesh-bg">
        <div class="gradient-mesh-blob"></div>
    </div>

    <div class="mesh-overlay"></div>

    <div class="glass-container" style="margin-top: 5vh; margin-bottom: 5vh; width: 92%;">
        <div class="glass-header">
            <img src="{{ asset('images/logo-dark.png') }}" alt="SEGRO" class="glass-logo">
            <h1>Create Account</h1>
            <p>Join the premier concierge experience</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-error">
                <div>
                    @foreach ($errors->all() as $error)
                        <p style="margin-bottom: 4px;">{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('user-registeration') }}">
            @csrf
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Choose a username" required>
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="you@example.com" required autocomplete="email">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <div class="pw-input-group">
                    <input type="password" id="password" name="password" placeholder="••••••••" required>
                    <button type="button" onclick="generateLoginPassword()">Generate</button>
                </div>
            </div>
            <div class="form-group">
                <label for="confirm-password">Confirm Password</label>
                <input type="password" id="confirm-password" name="password_confirmation" placeholder="••••••••" required>
            </div>
            <div class="form-group">
                <label for="wallet-password">Wallet Password</label>
                <div class="pw-input-group">
                    <input type="password" id="wallet-password" name="wallet-password" placeholder="••••••••" required>
                    <button type="button" onclick="generateWalletPassword()">Generate</button>
                </div>
            </div>
            <div class="form-group">
                <label for="refrence-code">Reference Code</label>
                <input type="text" id="refrence-code" name="refrence-code" placeholder="Enter reference code" value="{{ old('refrence-code', request()->query('refrence-code')) }}" required>
            </div>
            <button type="submit" class="btn btn-primary" style="margin-top: 10px; margin-bottom: 20px; width: 100%;">Sign Up</button>
            <div class="text-center" style="font-size: 13px;">
                <a href="/user-login">Already have an account? Sign In</a>
            </div>
        </form>
    </div>

    <script src="{{ asset('js/modern_scripts.js') }}"></script>
    <script src="{{ asset('js/register.js') }}"></script>
</body>
</html>
