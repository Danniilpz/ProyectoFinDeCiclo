<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Lang;

class MyVerifyEmail extends VerifyEmail
{
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(Lang::getFromJson('Verificación de email'))
            ->line(Lang::getFromJson('Por favor haz click en el botón inferior para verificar tu email de tu cuenta de Loopz.'))
            ->action(
                Lang::getFromJson('Verificar email'),
                $this->verificationUrl($notifiable)
            )
            ->line(Lang::getFromJson('Si no has creado una cuenta, ignora este mensaje.'));
    }
}
