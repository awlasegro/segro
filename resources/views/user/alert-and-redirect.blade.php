<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Order Alert - Segro</title>
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pages/alert-and-redirect.css') }}">
</head>
<body>
    <div class="modal-card">
        <div class="alert-icon-wrapper">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
        </div>
        <h3 class="alert-title">Pending Order</h3>
        <p class="alert-message">{{ $message }}</p>
        <button onclick="proceed()" class="btn-proceed">Proceed</button>
    </div>

    <script type="text/javascript">
        function proceed() {
            window.location.href = "{{ $redirect_url }}";
        }
    </script>
</body>
</html>
