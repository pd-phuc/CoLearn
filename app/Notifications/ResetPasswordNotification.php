<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordBase;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends ResetPasswordBase
{
    /**
     * Build the mail representation of the notification in English.
     *
     * @param  mixed  $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        $url = route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);

        return (new MailMessage)
            ->subject('[CoLearn] Password Reset Verification')
            ->greeting('Hello!')
            ->line('You are receiving this email because we received a password reset request for your account.')
            ->action('Reset Password', $url)
            ->line('This password reset link will expire in '.config('auth.passwords.'.config('auth.defaults.passwords').'.expire').' minutes.')
            ->line('If you did not request a password reset, no further action is required.');
    }
}
