<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\NotificationService;

class AbuseReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'url',
        'email',
        'reason',
        'ip_address',
        'status',
    ];

    protected static function booted()
    {
        static::created(function ($abuseReport) {
            // Send notification to admin when new abuse report is created
            try {
                $notificationService = app(NotificationService::class);
                $notificationService->notifyAbuseReport([
                    'report_id' => $abuseReport->id,
                    'slug' => $abuseReport->slug,
                    'url' => $abuseReport->url,
                    'email' => $abuseReport->email,
                    'reason' => $abuseReport->reason,
                    'ip_address' => $abuseReport->ip_address,
                    'status' => $abuseReport->status,
                    'reported_at' => $abuseReport->created_at->format('Y-m-d H:i:s'),
                ]);
            } catch (\Exception $e) {
                \Log::error('Failed to send abuse report notification', [
                    'error' => $e->getMessage(),
                    'report_id' => $abuseReport->id
                ]);
            }
        });
    }
}
