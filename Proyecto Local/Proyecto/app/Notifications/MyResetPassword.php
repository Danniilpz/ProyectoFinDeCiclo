<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Lang;

class MyResetPassword extends ResetPassword
{
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(Lang::getFromJson('Recuperación de contraseña'))
            ->line('Estás recibiendo este email porque hemos recibido una petición de recuperación de la contraseña de tu cuenta de Loopz.')
            ->action('Recuperar contraseña', route('password.reset', $this->token))
            ->line(Lang::getFromJson('Este link de recuperación de contraseña expirará en :count minutos.', ['count' => config('auth.passwords.users.expire')]))
            ->line('Si no hiciste ninguna petición, ignora este mensaje.');
    }
}
