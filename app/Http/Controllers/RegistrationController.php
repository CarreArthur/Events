<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRegistrationRequest;
use App\Models\Event;
use App\Models\Registration;
use App\Notifications\NewRegistrationNotification;
use App\Notifications\RegistrationNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RegistrationController extends Controller
{
    public function create(Event $event)
    {
        abort_unless($event->is_public, 404);

        return view('registrations.create', [
            'event' => $event,
            'invitation' => null,
            'isPrivate' => false,
        ]);
    }

    public function invite(string $token)
    {
        $invitation = Registration::where('invite_token', $token)->firstOrFail();
        $event = $invitation->event;

        if ($invitation->status !== 'INVITED') {
            abort(403);
        }

        return view('registrations.create', [
            'event' => $event,
            'invitation' => $invitation,
            'isPrivate' => ! $event->is_public,
        ]);
    }

    public function confirmInvite(string $token)
    {
        $invitation = Registration::where('invite_token', $token)->firstOrFail();
        $event = $invitation->event;

        if ($invitation->status !== 'INVITED') {
            return view('registrations.invite-confirmed', [
                'event' => $event,
                'alreadyConfirmed' => true,
                'capacityError' => null,
            ]);
        }

        if (!is_null($event->max_participants) && $invitation->is_attending) {
            $usedSeats = Registration::where('event_id', $event->id)
                ->where('status', 'REGISTERED')
                ->where('is_attending', true)
                ->count();

            $remainingSeats = $event->max_participants - $usedSeats;

            if ($remainingSeats < 1) {
                return view('registrations.invite-confirmed', [
                    'event' => $event,
                    'alreadyConfirmed' => false,
                    'capacityError' => "Desole, il ne reste plus de place pour cet evenement.",
                ]);
            }
        }

        $invitation->forceFill([
            'status' => 'REGISTERED',
            'registered_at' => now(),
        ])->save();

        $invitation->notify(new RegistrationNotification($invitation));
        $invitation->forceFill(['email_sent_at' => now()])->save();

        if ($event->user) {
            $event->user->notify(new NewRegistrationNotification($invitation));
        }

        return view('registrations.invite-confirmed', [
            'event' => $event,
            'alreadyConfirmed' => false,
            'capacityError' => null,
        ]);
    }

    public function store(StoreRegistrationRequest $request, Event $event)
    {
        $isPrivate = ! $event->is_public;
        $invitation = null;

        if ($isPrivate) {
            $invitation = Registration::where('event_id', $event->id)
                ->where('invite_token', $request->input('invite_token'))
                ->where('status', 'INVITED')
                ->first();

            if (! $invitation) {
                return back()
                    ->withInput()
                    ->withErrors(['invite_token' => "Invitation invalide ou deja utilisee."]);
            }
        }

        $data = $request->validated();
        $isAttending = (bool) ($data['is_attending'] ?? true);

        $maxGuests = $isPrivate ? 0 : (int) ($event->max_guests_per_registration ?? 0);
        $guestsCount = $isPrivate ? 0 : min((int) ($data['guests_count'] ?? 0), $maxGuests);

        $requestedSeats = $isAttending ? (1 + $guestsCount) : 1;

        return DB::transaction(function () use ($event, $data, $isAttending, $guestsCount, $requestedSeats, $isPrivate, $invitation) {

            if (!is_null($event->max_participants) && $isAttending) {
                $usedSeats = Registration::where('event_id', $event->id)
                    ->where('status', 'REGISTERED')
                    ->where('is_attending', true)
                    ->count();

                $remainingSeats = $event->max_participants - $usedSeats;

                if ($requestedSeats > $remainingSeats) {
                    return back()
                        ->withInput()
                        ->withErrors(['capacity' => "Désolé, il ne reste que {$remainingSeats} place(s) disponible(s)."]);
                }
            }

            if ($isPrivate) {
                $principal = $invitation;
                $principal->fill([
                    'guest_name' => $data['guest_name'],
                    'guest_email' => $data['guest_email'],
                    'status' => 'REGISTERED',
                    'is_attending' => $isAttending,
                    'dietary_info' => $data['dietary_info'] ?? null,
                    'registered_at' => now(),
                ])->save();
            } else {
                $principal = Registration::create([
                    'event_id' => $event->id,
                    'parent_registration_id' => null,
                    'invite_token' => (string) Str::uuid(),
                    'guest_name' => $data['guest_name'],
                    'guest_email' => $data['guest_email'],
                    'status' => 'REGISTERED',
                    'is_attending' => $isAttending,
                    'dietary_info' => $data['dietary_info'] ?? null,
                    'registered_at' => now(),
                ]);
            }

            // ✅ Envoi email (MAIL_MAILER=log donc tu verras ça dans storage/logs/laravel.log)
            $principal->notify(new RegistrationNotification($principal));
            $principal->forceFill(['email_sent_at' => now()])->save();

            if ($event->user) {
                $event->user->notify(new NewRegistrationNotification($principal));
            }

            for ($i = 0; $i < $guestsCount; $i++) {
                $guestName = $data['guests'][$i]['name'] ?? null;
                $guestEmail = $data['guests'][$i]['email'] ?? null;

                Registration::create([
                    'event_id' => $event->id,
                    'parent_registration_id' => $principal->id,
                    'invite_token' => (string) Str::uuid(),
                    'guest_name' => $guestName,
                    'guest_email' => $guestEmail ?? $principal->guest_email,
                    'status' => 'REGISTERED',
                    'is_attending' => $isAttending,
                    'registered_at' => now(),
                ]);
            }

            return redirect()
                ->route('events.show', $event->slug)
                ->with('success', 'Inscription enregistrée avec succès ✅');
        });
    }

    public function cancel(string $token)
    {
        $registration = Registration::where('invite_token', $token)->firstOrFail();
        $event = $registration->event;

        // Vérifier que l'inscription est bien enregistrée
        if ($registration->status !== 'REGISTERED') {
            return view('registrations.cancel-confirmed', [
                'event' => $event,
                'alreadyCancelled' => true,
                'error' => null,
            ]);
        }

        // Ne permettre l'annulation que pour les événements publics
        if (!$event->is_public) {
            return view('registrations.cancel-confirmed', [
                'event' => $event,
                'alreadyCancelled' => false,
                'error' => "L'annulation n'est pas disponible pour les événements privés.",
            ]);
        }

        // Annuler l'inscription principale
        $registration->forceFill([
            'status' => 'CANCELLED',
        ])->save();

        // Annuler également les invités associés
        Registration::where('parent_registration_id', $registration->id)
            ->where('status', 'REGISTERED')
            ->update(['status' => 'CANCELLED']);

        return view('registrations.cancel-confirmed', [
            'event' => $event,
            'alreadyCancelled' => false,
            'error' => null,
        ]);
    }
}
