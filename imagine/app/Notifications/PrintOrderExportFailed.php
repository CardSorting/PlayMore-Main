<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class PrintOrderExportFailed extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected string $error
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database', 'slack'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->error()
            ->subject('Print Orders Export Failed')
            ->greeting('Hello ' . $notifiable->name)
            ->line('We encountered an error while generating your print orders export.')
            ->line('Error details: ' . $this->error)
            ->line('Our technical team has been notified and will investigate the issue.')
            ->action('Try Again', route('admin.prints.index'))
            ->line('If you continue to experience issues, please contact support.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'print_order_export_failed',
            'message' => 'Print orders export failed: ' . $this->error,
            'error' => $this->error,
            'occurred_at' => now(),
        ];
    }

    public function toSlack(object $notifiable): SlackMessage
    {
        return (new SlackMessage)
            ->error()
            ->content('Print Orders Export Failed')
            ->attachment(function ($attachment) use ($notifiable) {
                $attachment
                    ->title('Error Details')
                    ->fields([
                        'User' => $notifiable->name . ' (' . $notifiable->email . ')',
                        'Error' => $this->error,
                        'Time' => now()->format('Y-m-d H:i:s'),
                    ]);
            });
    }

    /**
     * Determine which queues should be used for each notification channel.
     *
     * @return array<string, string>
     */
    public function viaQueues(): array
    {
        return [
            'mail' => 'notifications',
            'slack' => 'notifications-slack',
            'database' => 'notifications',
        ];
    }

    /**
     * Get the retry delay for failed notifications.
     */
    public function retryAfter(): int
    {
        return 60; // 1 minute
    }

    /**
     * Get the maximum number of retries for failed notifications.
     */
    public function maxRetries(): int
    {
        return 3;
    }

    /**
     * Get the tags that should be assigned to the job.
     *
     * @return array<string>
     */
    public function tags(): array
    {
        return ['print-orders', 'export', 'error'];
    }
}
