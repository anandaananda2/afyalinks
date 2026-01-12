{{-- resources/views/welcome.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ config('app.name', 'AfyaLinks') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        * {
            box-sizing: border-box;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: #f4f6f8;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            width: 100%;
            max-width: 420px;
            background: #ffffff;
            padding: 32px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            text-align: center;
        }

        .logo {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 8px;
            color: #1f2937;
        }

        .subtitle {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 28px;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 12px;
            border-radius: 6px;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            margin-bottom: 12px;
        }

        .btn-primary {
            background: #2563eb;
            color: #ffffff;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        .btn-secondary {
            border: 1px solid #d1d5db;
            color: #111827;
            background: #ffffff;
        }

        .btn-secondary:hover {
            background: #f9fafb;
        }

        .footer {
            margin-top: 24px;
            font-size: 12px;
            color: #9ca3af;
        }
    </style>
</head>
<body>

    <div class="card">
        <div class="logo">{{ config('app.name', 'AfyaLinks') }}</div>
        <div class="subtitle">Clinic access portal</div>

        <a href="{{ route('login') }}" class="btn btn-primary">
            Sign in
        </a>

        @if (Route::has('register'))
            <a href="{{ route('register') }}" class="btn btn-secondary">
                Create account
            </a>
        @endif

        <div class="footer">
            © {{ date('Y') }}
        </div>
    </div>

</body>
</html>
