<?php

namespace App\Notifications;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReviewRequestNotification extends Notification
{
    use Queueable;

    public function __construct(public Registration $registration)
    {
    }

    public function via(mixed $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(mixed $notifiable): MailMessage
    {
        $event = $this->registration->event;

        return (new MailMessage)
            ->subject("Votre avis pour {$event->title}")
            ->greeting('Bonjour')
            ->line("Merci d'avoir participe a {$event->title}.")
            ->line('Votre avis nous aide a ameliorer les prochains evenements.')
            ->action("Laisser mon avis", route('reviews.create', $this->registration->invite_token))
            ->line('Merci et a bientot !');
    }
}
