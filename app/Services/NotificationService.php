<?php

namespace App\Services;

use App\Mail\AdminNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send notification to admin about abuse reports
     */
    public function notifyAbuseReport(array $data): void
    {
        try {
            Mail::to(config('mail.addresses.admin'))
                ->send(new AdminNotification(
                    'abuse_report',
                    '🚨 New Abuse Report Received',
                    $data
                ));
        } catch (\Exception $e) {
            Log::error('Failed to send abuse report notification', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
        }
    }

    /**
     * Send notification about new user registration
     */
    public function notifyNewUser(array $data): void
    {
        try {
            Mail::to(config('mail.addresses.admin'))
                ->send(new AdminNotification(
                    'new_user',
                    '👤 New User Registration',
                    $data
                ));
        } catch (\Exception $e) {
            Log::error('Failed to send new user notification', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
        }
    }

    /**
     * Send security alert notification
     */
    public function notifySecurityAlert(array $data): void
    {
        try {
            Mail::to(config('mail.addresses.security'))
                ->send(new AdminNotification(
                    'security_alert',
                    '⚠️ Security Alert',
                    $data
                ));
        } catch (\Exception $e) {
            Log::error('Failed to send security alert', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
        }
    }

    /**
     * Send suspicious activity notification
     */
    public function notifySuspiciousActivity(array $data): void
    {
        try {
            Mail::to(config('mail.addresses.security'))
                ->send(new AdminNotification(
                    'suspicious_activity',
                    '🔍 Suspicious Activity Detected',
                    $data
                ));
        } catch (\Exception $e) {
            Log::error('Failed to send suspicious activity notification', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
        }
    }

    /**
     * Send feedback notification
     */
    public function notifyFeedback(array $data): void
    {
        try {
            Mail::to(config('mail.addresses.feedback'))
                ->send(new AdminNotification(
                    'feedback',
                    '💭 New User Feedback',
                    $data
                ));
        } catch (\Exception $e) {
            Log::error('Failed to send feedback notification', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
        }
    }

    /**
     * Send support request notification
     */
    public function notifySupportRequest(array $data): void
    {
        try {
            Mail::to(config('mail.addresses.support'))
                ->send(new AdminNotification(
                    'support_request',
                    '🎫 New Support Request',
                    $data
                ));
        } catch (\Exception $e) {
            Log::error('Failed to send support request notification', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
        }
    }

    /**
     * Send contact form notification
     */
    public function notifyContactForm(array $data): void
    {
        try {
            Mail::to(config('mail.addresses.support'))
                ->send(new AdminNotification(
                    'contact_form',
                    '📧 New Contact Form Submission',
                    $data
                ));
        } catch (\Exception $e) {
            Log::error('Failed to send contact form notification', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
        }
    }

    /**
     * Send system error notification to admin
     */
    public function notifySystemError(array $data): void
    {
        try {
            Mail::to(config('mail.addresses.admin'))
                ->send(new AdminNotification(
                    'system_error',
                    '🔥 System Error Alert',
                    $data
                ));
        } catch (\Exception $e) {
            Log::error('Failed to send system error notification', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
        }
    }

    /**
     * Send critical exception notification to admin
     */
    public function notifyCriticalException(\Throwable $exception, array $context = []): void
    {
        try {
            $data = [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString(),
                'context' => $context,
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'timestamp' => now()->toDateTimeString(),
            ];

            Mail::to(config('mail.addresses.admin'))
                ->send(new AdminNotification(
                    'critical_exception',
                    '🚨 Critical Exception: ' . class_basename($exception),
                    $data
                ));
        } catch (\Exception $e) {
            Log::error('Failed to send critical exception notification', [
                'error' => $e->getMessage(),
                'original_exception' => $exception->getMessage()
            ]);
        }
    }

    /**
     * Send feature request notification
     */
    public function notifyFeatureRequest(array $data): void
    {
        try {
            Mail::to(config('mail.addresses.feedback'))
                ->send(new AdminNotification(
                    'feature_request',
                    '💡 New Feature Request',
                    $data
                ));
        } catch (\Exception $e) {
            Log::error('Failed to send feature request notification', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
        }
    }
}
