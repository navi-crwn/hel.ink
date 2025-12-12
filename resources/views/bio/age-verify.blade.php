<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HEL.ink - Age Verification</title>
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
            background: linear-gradient(135deg, #1a0a0a 0%, #2d1515 50%, #1a0a0a 100%);
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
            background: radial-gradient(circle, rgba(239, 68, 68, 0.1) 0%, transparent 50%),
                        radial-gradient(circle at 80% 20%, rgba(185, 28, 28, 0.1) 0%, transparent 40%);
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
            background: rgba(30, 20, 20, 0.9);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 2.5rem 2rem;
            border: 1px solid rgba(239, 68, 68, 0.2);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7);
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
        .warning-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1rem;
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.3), rgba(185, 28, 28, 0.3));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid rgba(239, 68, 68, 0.5);
        }
        .warning-icon svg {
            width: 40px;
            height: 40px;
            color: #ef4444;
        }
        .age-badge {
            display: flex;
            justify-content: center;
            margin-bottom: 1.5rem;
        }
        .age-badge span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 72px;
            height: 72px;
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            border-radius: 50%;
            color: #fff;
            font-weight: 800;
            font-size: 1.75rem;
            box-shadow: 0 10px 30px -10px rgba(220, 38, 38, 0.6);
            border: 3px solid rgba(255, 255, 255, 0.2);
        }
        .header {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 0.5rem;
        }
        .header p {
            color: #a1a1aa;
            font-size: 0.875rem;
            line-height: 1.5;
        }
        .page-title {
            color: #e4e4e7;
            font-size: 0.95rem;
            font-weight: 500;
            margin-top: 0.75rem;
            padding: 0.5rem 1rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            display: inline-block;
        }
        .disclaimer {
            color: #a1a1aa;
            font-size: 0.8rem;
            text-align: center;
            margin-bottom: 1.5rem;
            line-height: 1.6;
            padding: 0.75rem;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .enter-btn {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            border: none;
            border-radius: 12px;
            color: #fff;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
        }
        .enter-btn:hover {
            background: linear-gradient(135deg, #b91c1c, #991b1b);
            transform: translateY(-1px);
            box-shadow: 0 10px 25px -10px rgba(220, 38, 38, 0.6);
        }
        .enter-btn svg {
            width: 20px;
            height: 20px;
        }
        .leave-btn {
            width: 100%;
            padding: 0.875rem;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            color: #d4d4d8;
            font-size: 0.95rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: block;
            text-align: center;
        }
        .leave-btn:hover {
            background: rgba(255, 255, 255, 0.15);
            color: #fff;
        }
        .footer-disclaimer {
            color: #71717a;
            font-size: 0.7rem;
            text-align: center;
            margin-top: 1.5rem;
            line-height: 1.5;
        }
        .branding {
            text-align: center;
            margin-top: 1.5rem;
        }
        .branding p {
            color: #52525b;
            font-size: 0.75rem;
        }
        .branding a {
            color: #71717a;
            text-decoration: none;
            transition: color 0.2s;
        }
        .branding a:hover {
            color: #ef4444;
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
            <div class="warning-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div class="age-badge">
                <span>18+</span>
            </div>
            <div class="header">
                <h1>Age Verification Required</h1>
                <p>This page contains content intended for adults only</p>
                @if($bioPage->title)
                    <span class="page-title">{{ $bioPage->title }}</span>
                @endif
            </div>
            <div class="disclaimer">
                By clicking "I'm 18+ Enter", you confirm that you are at least 18 years old and agree to view adult content.
            </div>
            <form method="POST" action="{{ url()->current() }}">
                @csrf
                <input type="hidden" name="age_confirm" value="yes">
                <button type="submit" class="enter-btn">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    I'm 18+ Enter
                </button>
                <a href="{{ url('/') }}" class="leave-btn">Leave Page</a>
            </form>
            <p class="footer-disclaimer">
                By continuing, you agree that you are of legal age to view adult content in your jurisdiction.
            </p>
        </div>
        <div class="branding">
            <p>Powered by <a href="{{ url('/') }}">HEL.ink</a></p>
        </div>
    </div>
</body>
</html>
