<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Password;

class UserInvitationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected string $token;

    public function __construct()
    {
        //
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $token = Password::broker()->createToken($notifiable);
        $url = url(route('password.reset', [
            'token' => $token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Bienvenue sur ' . config('app.name') . ' - Créez votre mot de passe')
            ->greeting('Bonjour ' . $notifiable->name . ' !')
            ->line('Vous avez été invité(e) à rejoindre la plateforme **' . config('app.name') . '**.')
            ->line('Pour activer votre compte, veuillez créer votre mot de passe en cliquant sur le bouton ci-dessous.')
            ->action('Créer mon mot de passe', $url)
            ->line('Ce lien expirera dans 60 minutes.')
            ->line('Si vous n\'avez pas demandé cette invitation, vous pouvez ignorer cet email.')
            ->salutation('Cordialement, L\'équipe ' . config('app.name'));
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}
