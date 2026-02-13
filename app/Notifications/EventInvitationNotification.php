<?php

namespace App\Notifications;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EventInvitationNotification extends Notification
{
    use Queueable;

    public function __construct(public Registration $invitation)
    {
    }

    public function via(mixed $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(mixed $notifiable): MailMessage
    {
        $event = $this->invitation->event;

        return (new MailMessage)
            ->subject("Invitation — {$event->title}")
            ->greeting('Bonjour')
            ->line("Vous etes invite(e) a l'evenement : {$event->title}")
            ->line('Merci de confirmer votre presence via le lien ci-dessous.')
            ->action('Confirmer ma presence', route('registrations.confirm', $this->invitation->invite_token))
            ->line('A bientot !');
    }
}
