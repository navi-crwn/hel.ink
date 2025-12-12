<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to {{ config('app.name') }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            border-radius: 8px;
            padding: 40px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 32px;
            font-weight: bold;
            color: #3B82F6;
            margin-bottom: 10px;
        }
        h1 {
            color: #1F2937;
            font-size: 24px;
            margin-bottom: 20px;
        }
        .welcome-message {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .catchphrase-box {
            background: #FEF3C7;
            border-left: 4px solid #F59E0B;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .catchphrase-box strong {
            color: #92400E;
            font-size: 18px;
        }
        .tutorial-section {
            background: #EFF6FF;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .tutorial-section h2 {
            color: #1E40AF;
            font-size: 18px;
            margin-top: 0;
        }
        .step {
            margin: 15px 0;
            padding-left: 30px;
            position: relative;
        }
        .step::before {
            content: "✓";
            position: absolute;
            left: 0;
            top: 0;
            background: #10B981;
            color: white;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
        }
        .security-reminder {
            background: #FEE2E2;
            border-left: 4px solid #DC2626;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .security-reminder h3 {
            color: #991B1B;
            margin-top: 0;
            font-size: 16px;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: #3B82F6;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 20px 0;
        }
        .button:hover {
            background: #2563EB;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #E5E7EB;
            color: #6B7280;
            font-size: 14px;
        }
        .feature-list {
            list-style: none;
            padding: 0;
        }
        .feature-list li {
            padding: 8px 0;
            padding-left: 25px;
            position: relative;
        }
        .feature-list li::before {
            content: "🔗";
            position: absolute;
            left: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">🔗 {{ config('app.name') }}</div>
        </div>
        <div class="welcome-message">
            <h1 style="color: white; margin: 0;">Welcome, {{ $user->name }}! 🎉</h1>
            <p style="margin: 10px 0 0 0;">Thank you for joining {{ config('app.name') }}. We're excited to have you on board!</p>
        </div>
        <p>Hi {{ $user->name }},</p>
        <p>Your account has been successfully created and is ready to use. You can now start creating short links, tracking analytics, and organizing your URLs efficiently.</p>
        @if($isGoogleUser)
        <p><strong>📧 You signed up with Google Account</strong></p>
        <p>For added security, we recommend setting up a password for your account. This allows you to login even when Google OAuth is unavailable.</p>
        @endif
        <div class="catchphrase-box">
            <p style="margin: 0 0 10px 0;"><strong>🔐 Your Security Catchphrase:</strong></p>
            <p style="font-size: 20px; font-weight: bold; margin: 0; color: #92400E; font-family: monospace;">{{ $catchphrase }}</p>
        </div>
        <div class="security-reminder">
            <h3>🛡️ Important Security Reminders</h3>
            <ul style="margin: 10px 0; padding-left: 20px;">
                <li><strong>Save your catchphrase</strong> - You'll need it for password recovery</li>
                <li><strong>Keep your password secure</strong> - Never share it with anyone</li>
                <li><strong>Enable 2FA</strong> - Add an extra layer of security to your account</li>
                <li><strong>Review login history</strong> - Check for any suspicious activity regularly</li>
            </ul>
        </div>
        <div class="tutorial-section">
            <h2>🚀 Quick Start Guide</h2>
            <div class="step">
                <strong>Create Your First Short Link</strong><br>
                Click "Create Link" on your dashboard and enter any URL you want to shorten.
            </div>
            <div class="step">
                <strong>Organize with Folders & Tags</strong><br>
                Keep your links organized by creating folders and adding tags for easy filtering.
            </div>
            <div class="step">
                <strong>Track Analytics</strong><br>
                View detailed click statistics, geographic data, and visitor insights for each link.
            </div>
            <div class="step">
                <strong>Generate QR Codes</strong><br>
                Every short link comes with a customizable QR code for offline sharing.
            </div>
            <div class="step">
                <strong>Set Advanced Options</strong><br>
                Add password protection, expiration dates, and redirect types to your links.
            </div>
        </div>
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ url('/dashboard') }}" class="button">Go to Dashboard →</a>
        </div>
        <div style="background: #F9FAFB; padding: 20px; border-radius: 8px; margin: 20px 0;">
            <h3 style="margin-top: 0; color: #374151;">✨ What You Can Do with {{ config('app.name') }}</h3>
            <ul class="feature-list">
                <li>Create unlimited custom short links</li>
                <li>Track clicks with real-time analytics</li>
                <li>Organize links with folders and tags</li>
                <li>Generate branded QR codes</li>
                <li>Password protect sensitive links</li>
                <li>Set link expiration dates</li>
                <li>Export data for analysis</li>
                <li>Access from any device with our responsive design</li>
            </ul>
        </div>
        <p>If you have any questions or need help getting started, feel free to:</p>
        <ul>
            <li>Visit our <a href="{{ url('/help') }}" style="color: #3B82F6;">Help Center</a></li>
            <li>Check out the <a href="{{ url('/faq') }}" style="color: #3B82F6;">FAQ</a></li>
            <li>Contact us at <a href="mailto:{{ config('mail.from.address') }}" style="color: #3B82F6;">{{ config('mail.from.address') }}</a></li>
        </ul>
        <p>Thanks again for choosing {{ config('app.name') }}. We're here to help you succeed!</p>
        <p style="margin-top: 30px;">
            Best regards,<br>
            <strong>The {{ config('app.name') }} Team</strong>
        </p>
        <div class="footer">
            <p>This email was sent to {{ $user->email }}</p>
            <p>{{ config('app.name') }} - Modern URL Shortening Service</p>
            <p style="font-size: 12px; color: #9CA3AF; margin-top: 10px;">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
