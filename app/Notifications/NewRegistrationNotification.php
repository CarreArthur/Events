<?php

namespace App\Notifications;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewRegistrationNotification extends Notification
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
        $guestName = $this->registration->guest_name ?? 'Invite';
        $guestEmail = $this->registration->guest_email ?? '-';

        return (new MailMessage)
            ->subject("Nouvelle inscription — {$event->title}")
            ->greeting('Bonjour')
            ->line("Nouvelle inscription pour : {$event->title}")
            ->line("Nom : {$guestName}")
            ->line("Email : {$guestEmail}")
            ->line("Presence : " . ($this->registration->is_attending ? 'Oui' : 'Non'))
            ->when(! empty($this->registration->dietary_info), fn (MailMessage $m) => $m->line("Contraintes : {$this->registration->dietary_info}"))
            ->line('Consultez le tableau de bord pour le detail.');
    }
}
