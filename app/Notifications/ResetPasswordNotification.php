<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    protected $token;
    protected $isNewAccount;

    public function __construct($token, $isNewAccount = false)
    {
        $this->token = $token;
        $this->isNewAccount = $isNewAccount;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $actionType = $this->isNewAccount ? 'set' : 'reset';
        $buttonLabel = $this->isNewAccount ? 'Set Kata Laluan' : 'Reset Kata Laluan';
    
        $mailMessage = (new MailMessage)
            ->subject($this->isNewAccount ? 'Akaun Baru - Set Kata Laluan' : 'Reset Kata Laluan Anda')
            ->line($this->isNewAccount 
                ? 'Akaun baru telah dibina menggunakan emel ini. Sila set kata laluan anda.' 
                : 'Anda menerima emel ini kerana kami menerima permohonan untuk reset kata laluan.')
            ->action($buttonLabel, url(route('password.reset', [
                'token' => $this->token, 
                'email' => $notifiable->getEmailForPasswordReset(), 
                'type' => $actionType 
            ], false)))
            ->line('Sila abaikan emel ini jika anda tidak membuat permohonan reset kata laluan.');
    
        return $mailMessage;
    }
    
    

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
