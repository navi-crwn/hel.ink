<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Notification</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 30px 20px;
        }
        .notification-type {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 20px;
        }
        .type-abuse_report { background-color: #fee; color: #c00; }
        .type-new_user { background-color: #e7f5ff; color: #0066cc; }
        .type-security_alert { background-color: #fff3cd; color: #856404; }
        .type-suspicious_activity { background-color: #fff3cd; color: #856404; }
        .type-feedback { background-color: #e8f5e9; color: #2e7d32; }
        .type-support_request { background-color: #e3f2fd; color: #1565c0; }
        .type-contact_form { background-color: #e3f2fd; color: #1565c0; }
        .type-system_error { background-color: #ffebee; color: #c62828; }
        .type-critical_exception { background-color: #ffebee; color: #c62828; }
        .type-feature_request { background-color: #f3e5f5; color: #6a1b9a; }
        .data-section {
            background-color: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .data-item {
            margin: 10px 0;
        }
        .data-label {
            font-weight: 600;
            color: #555;
            display: inline-block;
            min-width: 120px;
        }
        .data-value {
            color: #333;
        }
        .code-block {
            background-color: #2d2d2d;
            color: #f8f8f2;
            padding: 15px;
            border-radius: 4px;
            overflow-x: auto;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.5;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #e9ecef;
        }
        .timestamp {
            color: #999;
            font-size: 13px;
            margin-top: 15px;
        }
        .alert-high {
            border-left-color: #dc3545;
        }
        .alert-medium {
            border-left-color: #ffc107;
        }
        .alert-low {
            border-left-color: #28a745;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ config('app.name') }} - Admin Notification</h1>
        </div>
        
        <div class="content">
            <span class="notification-type type-{{ $notificationType }}">
                {{ str_replace('_', ' ', $notificationType) }}
            </span>

            @if($notificationType === 'abuse_report')
                <div class="data-section alert-high">
                    <h3 style="margin-top: 0; color: #c00;">🚨 Abuse Report Details</h3>
                    @foreach($data as $key => $value)
                        <div class="data-item">
                            <span class="data-label">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                            <span class="data-value">{{ is_array($value) ? json_encode($value) : $value }}</span>
                        </div>
                    @endforeach
                </div>

            @elseif($notificationType === 'new_user')
                <div class="data-section alert-low">
                    <h3 style="margin-top: 0; color: #0066cc;">👤 New User Information</h3>
                    @foreach($data as $key => $value)
                        <div class="data-item">
                            <span class="data-label">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                            <span class="data-value">{{ is_array($value) ? json_encode($value) : $value }}</span>
                        </div>
                    @endforeach
                </div>

            @elseif($notificationType === 'security_alert' || $notificationType === 'suspicious_activity')
                <div class="data-section alert-high">
                    <h3 style="margin-top: 0; color: #856404;">⚠️ Security Alert</h3>
                    @foreach($data as $key => $value)
                        <div class="data-item">
                            <span class="data-label">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                            <span class="data-value">{{ is_array($value) ? json_encode($value) : $value }}</span>
                        </div>
                    @endforeach
                </div>

            @elseif($notificationType === 'system_error' || $notificationType === 'critical_exception')
                <div class="data-section alert-high">
                    <h3 style="margin-top: 0; color: #c62828;">🔥 Error Details</h3>
                    
                    @if(isset($data['message']))
                        <div class="data-item">
                            <span class="data-label">Message:</span>
                            <span class="data-value">{{ $data['message'] }}</span>
                        </div>
                    @endif

                    @if(isset($data['file']))
                        <div class="data-item">
                            <span class="data-label">File:</span>
                            <span class="data-value">{{ $data['file'] }}</span>
                        </div>
                    @endif

                    @if(isset($data['line']))
                        <div class="data-item">
                            <span class="data-label">Line:</span>
                            <span class="data-value">{{ $data['line'] }}</span>
                        </div>
                    @endif

                    @if(isset($data['url']))
                        <div class="data-item">
                            <span class="data-label">URL:</span>
                            <span class="data-value">{{ $data['url'] }}</span>
                        </div>
                    @endif

                    @if(isset($data['method']))
                        <div class="data-item">
                            <span class="data-label">Method:</span>
                            <span class="data-value">{{ $data['method'] }}</span>
                        </div>
                    @endif

                    @if(isset($data['ip']))
                        <div class="data-item">
                            <span class="data-label">IP Address:</span>
                            <span class="data-value">{{ $data['ip'] }}</span>
                        </div>
                    @endif

                    @if(isset($data['user_agent']))
                        <div class="data-item">
                            <span class="data-label">User Agent:</span>
                            <span class="data-value">{{ $data['user_agent'] }}</span>
                        </div>
                    @endif

                    @if(isset($data['trace']))
                        <h4 style="margin-top: 20px; margin-bottom: 10px;">Stack Trace:</h4>
                        <div class="code-block">{{ $data['trace'] }}</div>
                    @endif

                    @if(isset($data['context']) && !empty($data['context']))
                        <h4 style="margin-top: 20px; margin-bottom: 10px;">Context:</h4>
                        <div class="code-block">{{ json_encode($data['context'], JSON_PRETTY_PRINT) }}</div>
                    @endif
                </div>

            @elseif($notificationType === 'feedback' || $notificationType === 'feature_request')
                <div class="data-section alert-low">
                    <h3 style="margin-top: 0; color: #2e7d32;">💭 Feedback Details</h3>
                    @foreach($data as $key => $value)
                        <div class="data-item">
                            <span class="data-label">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                            <span class="data-value">{{ is_array($value) ? json_encode($value) : $value }}</span>
                        </div>
                    @endforeach
                </div>

            @elseif($notificationType === 'support_request' || $notificationType === 'contact_form')
                <div class="data-section alert-medium">
                    <h3 style="margin-top: 0; color: #1565c0;">🎫 Support Request</h3>
                    @foreach($data as $key => $value)
                        <div class="data-item">
                            <span class="data-label">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                            <span class="data-value">{{ is_array($value) ? json_encode($value) : $value }}</span>
                        </div>
                    @endforeach
                </div>

            @else
                <div class="data-section">
                    <h3 style="margin-top: 0;">Notification Details</h3>
                    @foreach($data as $key => $value)
                        <div class="data-item">
                            <span class="data-label">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                            <span class="data-value">{{ is_array($value) ? json_encode($value) : $value }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="timestamp">
                📅 Received: {{ now()->format('d M Y, H:i:s') }} (Server Time)
            </div>
        </div>

        <div class="footer">
            <p style="margin: 5px 0;">This is an automated notification from {{ config('app.name') }}</p>
            <p style="margin: 5px 0;">{{ config('app.url') }}</p>
        </div>
    </div>
</body>
</html>
