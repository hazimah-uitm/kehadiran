<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class EmailVerificationNotification extends Notification
{
    protected $user;
    protected $token;

    public function __construct($user, $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $verificationUrl = url('/verify-email/' . $this->token);

        return (new MailMessage)
            ->subject('Pengesahan Emel Akaun')
            ->greeting('Salam Sejahtera,')
            ->line('Sila klik pautan di bawah untuk mengesahkan akaun anda:')
            ->action('Sahkan Emel', $verificationUrl)
            ->line('Jika anda tidak mendaftar, abaikan emel ini.');
    }
}
