<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class InvitationNotification extends Notification
{
    use Queueable;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Invitation à rejoindre le Générateur CDC')
            ->greeting('Bonjour ' . $notifiable->name . ' !')
            ->line('Vous avez été invité à accéder à la plateforme de génération de Cahiers des Charges.')
            ->line('Pour commencer, vous devez définir votre mot de passe en cliquant sur le bouton ci-dessous :')
            ->action('Définir mon mot de passe', $url)
            ->line('Ce lien expirera dans 60 minutes.')
            ->line('Si vous n\'êtes pas à l\'origine de cette demande, vous pouvez ignorer cet email.')
            ->salutation('Cordialement, l\'équipe CDC');
    }
}
