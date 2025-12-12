<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HEL.ink - Password Protected</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" href="{{ route('brand.favicon') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
            position: relative;
            overflow: hidden;
        }
        body::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.1) 0%, transparent 50%),
                        radial-gradient(circle at 80% 20%, rgba(168, 85, 247, 0.1) 0%, transparent 40%);
            animation: float 20s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }
        .container {
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 1;
        }
        .card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 2.5rem 2rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        .logo-container {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .logo {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: #fff;
            text-decoration: none;
        }
        .logo img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }
        .logo-dot {
            color: #3b82f6;
        }
        .lock-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            background: linear-gradient(135deg, rgba(251, 191, 36, 0.2), rgba(245, 158, 11, 0.2));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(251, 191, 36, 0.3);
        }
        .lock-icon svg {
            width: 40px;
            height: 40px;
            color: #fbbf24;
        }
        .header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 0.5rem;
        }
        .header p {
            color: #94a3b8;
            font-size: 0.875rem;
        }
        .page-title {
            color: #e2e8f0;
            font-size: 0.95rem;
            font-weight: 500;
            margin-top: 0.75rem;
            padding: 0.5rem 1rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            display: inline-block;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .input-wrapper {
            position: relative;
        }
        .password-input {
            width: 100%;
            padding: 0.875rem 3.5rem 0.875rem 1rem;
            background: rgba(30, 41, 59, 0.8);
            border: 1px solid rgba(100, 116, 139, 0.3);
            border-radius: 12px;
            color: #fff;
            font-size: 1rem;
            outline: none;
            transition: all 0.2s;
        }
        .password-input::placeholder {
            color: #64748b;
        }
        .password-input:focus {
            border-color: #fbbf24;
            box-shadow: 0 0 0 3px rgba(251, 191, 36, 0.2);
        }
        .toggle-btn {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #64748b;
            cursor: pointer;
            padding: 0.5rem;
            font-size: 0.75rem;
            font-weight: 500;
            transition: color 0.2s;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        .toggle-btn:hover {
            color: #fbbf24;
        }
        .toggle-btn svg {
            width: 18px;
            height: 18px;
        }
        .error-message {
            color: #f87171;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .submit-btn {
            width: 100%;
            padding: 0.875rem;
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            border: none;
            border-radius: 12px;
            color: #1e293b;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        .submit-btn:hover {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            transform: translateY(-1px);
            box-shadow: 0 10px 20px -10px rgba(251, 191, 36, 0.5);
        }
        .submit-btn svg {
            width: 20px;
            height: 20px;
        }
        .back-link {
            text-align: center;
            margin-top: 1.5rem;
        }
        .back-link a {
            color: #64748b;
            text-decoration: none;
            font-size: 0.875rem;
            transition: color 0.2s;
        }
        .back-link a:hover {
            color: #fff;
        }
        .branding {
            text-align: center;
            margin-top: 1.5rem;
        }
        .branding p {
            color: #475569;
            font-size: 0.75rem;
        }
        .branding a {
            color: #64748b;
            text-decoration: none;
            transition: color 0.2s;
        }
        .branding a:hover {
            color: #3b82f6;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="logo-container">
                <a href="{{ url('/') }}" class="logo">
                    <img src="{{ route('brand.logo') }}" alt="HEL.ink">
                    HEL<span class="logo-dot">.</span>ink
                </a>
            </div>
            <div class="lock-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <div class="header">
                <h1>Password Protected</h1>
                <p>This page requires a password to view</p>
                @if($bioPage->title)
                    <span class="page-title">{{ $bioPage->title }}</span>
                @endif
            </div>
            <form method="POST" action="{{ url()->current() }}">
                @csrf
                <div class="form-group">
                    <div class="input-wrapper">
                        <input type="password" 
                               name="password" 
                               id="password"
                               class="password-input"
                               placeholder="Enter password" 
                               autofocus
                               required>
                        <button type="button" onclick="togglePassword()" class="toggle-btn" id="toggle-btn">
                            <svg id="eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <span id="toggle-text">Show</span>
                        </button>
                    </div>
                    @error('password')
                        <div class="error-message">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <button type="submit" class="submit-btn">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                    </svg>
                    Unlock Page
                </button>
            </form>
            <div class="back-link">
                <a href="{{ url('/') }}">← Back to home</a>
            </div>
        </div>
        <div class="branding">
            <p>Powered by <a href="{{ url('/') }}">HEL.ink</a></p>
        </div>
    </div>
    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const toggleText = document.getElementById('toggle-text');
            const eyeIcon = document.getElementById('eye-icon');
            
            if (input.type === 'password') {
                input.type = 'text';
                toggleText.textContent = 'Hide';
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>';
            } else {
                input.type = 'password';
                toggleText.textContent = 'Show';
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
            }
        }
    </script>
</body>
</html>
