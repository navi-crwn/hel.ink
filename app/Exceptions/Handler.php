<?php

namespace App\Exceptions;

use App\Services\NotificationService;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // Only notify on critical exceptions in production
            if ($this->shouldNotifyAdmin($e)) {
                try {
                    $notificationService = app(NotificationService::class);
                    $notificationService->notifyCriticalException($e, [
                        'environment' => config('app.env'),
                        'user_id' => auth()->id() ?? 'guest',
                    ]);
                } catch (\Exception $notificationException) {
                    // Log the notification failure but don't throw
                    \Log::error('Failed to send exception notification', [
                        'error' => $notificationException->getMessage(),
                    ]);
                }
            }
        });
    }

    /**
     * Determine if admin should be notified about this exception
     */
    protected function shouldNotifyAdmin(Throwable $e): bool
    {
        // Only notify in production or staging environments
        if (! in_array(config('app.env'), ['production', 'staging'])) {
            return false;
        }
        // Don't notify for common HTTP exceptions
        if ($e instanceof HttpException) {
            $statusCode = $e->getStatusCode();
            // Don't notify for 4xx errors (client errors)
            if ($statusCode >= 400 && $statusCode < 500) {
                return false;
            }
        }
        // Don't notify for rate limiting
        if ($e instanceof ThrottleRequestsException) {
            return false;
        }

        // Notify for all other exceptions (500 errors, database errors, etc.)
        return true;
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ThrottleRequestsException) {
            return response()->view('errors.429', [], 429);
        }

        return parent::render($request, $exception);
    }
}
