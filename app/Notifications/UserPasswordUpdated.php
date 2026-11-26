<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserPasswordUpdated extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */

    protected $resetRequest;

    public function __construct($resetRequest)
    {
        $this->resetRequest = $resetRequest;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Password Reset Request',
            'message' => 'A crew member has submitted a password reset request.',
            'phone_number' => $this->resetRequest->phone_number,
            'request_id' => $this->resetRequest->id,
            'type' => 'password_reset_requested',
        ];
    }
}
