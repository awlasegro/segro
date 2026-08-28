<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Login - Segro</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/modern_style.css') }}">
</head>
<body>
    <div class="gradient-mesh-bg">
        <div class="gradient-mesh-blob"></div>
    </div>

    <div class="mesh-overlay"></div>

    <div class="glass-container" style="margin-top: 15vh;">
        <div class="glass-header">
            <img src="{{ asset('images/logo-dark.png') }}" alt="SEGRO" class="glass-logo">
            <h1>Member Login</h1>
            <p>Access your elite dashboard and review tasks</p>
        </div>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
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

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn btn-primary" style="margin-top: 10px; margin-bottom: 20px; width: 100%;">Log In</button>
        </form>

        <div id="forgot-password-warning" class="alert alert-error" style="display: none; margin-top: 12px;">
            <span>Please contact our support team for account recovery.</span>
        </div>

        <div style="display: flex; justify-content: space-between; font-size: 13px;">
            <a href="#" onclick="document.getElementById('forgot-password-warning').style.display = 'flex'; return false;">Forgot Password?</a>
            <a href="/user-register">Create an Account</a>
        </div>
    </div>

    <script src="{{ asset('js/modern_scripts.js') }}"></script>
</body>
</html>
