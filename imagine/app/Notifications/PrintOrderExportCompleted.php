<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PrintOrderExportCompleted extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected string $downloadUrl
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Print Orders Export Ready')
            ->greeting('Hello ' . $notifiable->name)
            ->line('Your print orders export is ready for download.')
            ->action('Download Export', $this->downloadUrl)
            ->line('This download link will expire in 24 hours.')
            ->line('If you need to generate a new export, please visit the admin dashboard.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'print_order_export_completed',
            'message' => 'Your print orders export is ready for download.',
            'download_url' => $this->downloadUrl,
            'expires_at' => now()->addDay(),
        ];
    }
}
