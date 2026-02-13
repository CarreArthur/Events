<?php

namespace App\Notifications;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegistrationNotification extends Notification
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

        $message = (new MailMessage)
            ->subject("Confirmation d'inscription — {$event->title}")
            ->greeting("Bonjour " . ($this->registration->guest_name ?? ''))
            ->line("Votre inscription est bien enregistrée ✅")
            ->line("Événement : {$event->title}")
            ->line("Date : " . optional($event->date_start)->translatedFormat('d F Y à H:i'))
            ->when(!empty($event->location), fn (MailMessage $m) => $m->line("Lieu : {$event->location}"))
            ->when(!empty($this->registration->dietary_info), fn (MailMessage $m) => $m->line("Contraintes alimentaires : {$this->registration->dietary_info}"))
            ->action("Voir l'événement", url('/events/' . $event->slug));

        // Ajouter le bouton d'annulation uniquement pour les événements publics
        if ($event->is_public) {
            $message->action(
                "Annuler mon inscription",
                route('registrations.cancel', ['token' => $this->registration->invite_token])
            );
        }

        return $message->line("Merci et à bientôt !");
    }
}
