<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Brookfield Properties</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Page Styles -->
    <link rel="stylesheet" href="{{ asset('css/register-style.css') }}">
</head>
<body class="auth-page">
    <!-- Background video -->
    <div class="video-container" aria-hidden="true">
        <video autoplay muted loop playsinline>
            <source src="{{ asset('images/Legends-Login.mp4') }}" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>
    <div class="overlay" aria-hidden="true"></div>

    <!-- Auth Card -->
    <main class="auth-wrapper">
        <section class="auth-card" role="dialog" aria-labelledby="register-title" aria-describedby="register-subtitle">
            <img src="{{ asset('assets/images/Logo-white.png') }}" alt="Brookfield Properties" class="logo">
            <header class="login-header">
                <h1 id="register-title">Create your account</h1>
                <p id="register-subtitle">Join Brookfield Properties to get started</p>
            </header>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('user-registeration') }}" class="auth-form" novalidate>
                @csrf
                <div class="input-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Choose a username" required
                        pattern="^[a-zA-Z0-9._]+$"
                        title="Username can only contain letters, numbers, underscores, and dots. No spaces allowed.">
                </div>
                <div class="input-group">
                    <label for="full-name">Complete Name</label>
                    <input type="text" id="full-name" name="name" placeholder="Your full name" required>
                </div>
                <div class="input-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="you@example.com" required autocomplete="email">
                </div>
                <div class="input-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone" placeholder="Your phone number" required>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Create a password" required autocomplete="new-password">
                </div>
                <div class="input-group">
                    <label for="confirm-password">Confirm Password</label>
                    <input type="password" id="confirm-password" name="password_confirmation" placeholder="Re-enter your password" required autocomplete="new-password">
                </div>
                <div class="input-group">
                    <label for="wallet-password">Wallet Password</label>
                    <input type="password" id="wallet-password" name="wallet-password" placeholder="Wallet password" required>
                </div>
                <div class="input-group">
                    <label for="refrence-code">Reference Code</label>
                    <input type="text" id="refrence-code" name="refrence-code" placeholder="Enter your reference code" required>
                </div>
                <button type="submit" class="login-button">Create account</button>
            </form>

            <div class="additional-links">
                <a href="{{ url('user-login') }}">Already have an account? Sign in</a>
            </div>
        </section>
    </main>

    @include('user.layout.chat')

    <script>
    // Keep the username input sanitized client-side
    (function(){
        var u = document.getElementById('username');
        if(u){ u.addEventListener('input', function () { this.value = this.value.replace(/[^a-zA-Z0-9._]/g, ''); }); }
    })();
    </script>
</body>
</html>
