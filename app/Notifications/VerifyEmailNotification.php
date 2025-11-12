<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerifyEmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $token;
    public $user;

    /**
     * Create a new notification instance.
     */
    public function __construct($token, $user)
    {
        $this->token = $token;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $verificationUrl = route('auth.verify-email', [
            'token' => $this->token,
            'email' => $notifiable->email,
        ]);

        return (new MailMessage)
            ->subject('Verifikasi Email - Campus Market')
            ->greeting('Halo ' . $this->user->name . '!')
            ->line('Terima kasih telah mendaftar di Campus Market.')
            ->line('Silakan klik tombol di bawah untuk memverifikasi email Anda:')
            ->action('Verifikasi Email', $verificationUrl)
            ->line('Link verifikasi ini akan berlaku selama 24 jam.')
            ->line('Jika Anda tidak melakukan pendaftaran, abaikan email ini.')
            ->salutation('Salam,<br/>Tim Campus Market');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
