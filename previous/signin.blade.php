<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Brookfield Properties</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Page Styles -->
    <link rel="stylesheet" href="{{ asset('css/login-style.css') }}">
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
        <section class="auth-card" role="dialog" aria-labelledby="signin-title" aria-describedby="signin-subtitle">
            <img src="{{ asset('assets/images/Logo-white.png') }}" alt="Brookfield Properties" class="logo">

            <header class="login-header">
                <h1 id="signin-title">Welcome back</h1>
                <p id="signin-subtitle">Sign in to continue to Brookfield Properties</p>
            </header>

            @if (session('status'))
                <div class="alert info" role="status">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert error" role="alert">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="auth-form" novalidate>
                @csrf
                <div class="input-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Enter your username" required autocomplete="username" autofocus>
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required autocomplete="current-password">
                </div>

                <div class="form-row between">
                    <label class="checkbox">
                        <input type="checkbox" name="remember" id="remember">
                        <span>Remember me</span>
                    </label>
                    <div class="links-inline">
                        <button type="button" class="link-btn" onclick="document.getElementById('support-form').submit()">Forgot Password?</button>
                    </div>
                </div>

                <button type="submit" class="login-button">Sign In</button>
            </form>

            <div class="additional-links">
                <a href="{{ url('user-register') }}">Create an account</a>
            </div>
        </section>
    </main>

    <!-- Hidden support form to handle Forgot Password via POST -->
    <form id="support-form" action="{{ route('support') }}" method="POST" style="display:none;">
        @csrf
    </form>

    @include('user.layout.chat')

    @php
        if (auth()->check()) {
            header('Location: ' . url('/home'));
            exit;
        }
    @endphp
</body>
</html>
