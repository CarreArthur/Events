<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'guest_email.unique' => 'Cet email est déjà inscrit à cet événement.',
            'guest_email.required' => 'L\'email est requis.',
            'guest_email.email' => 'Veuillez entrer une adresse email valide.',
            'guest_name.required' => 'Le nom est requis.',
        ];
    }

    public function rules(): array
    {
        $event = $this->route('event');
        $isPrivate = $event && ! $event->is_public;
        $maxGuests = $isPrivate ? 0 : (int) min(2, (int) ($event?->max_guests_per_registration ?? 2));

        return [
            'guest_name' => ['required', 'string', 'max:255'],
            'guest_email' => [
                'required', 
                'email', 
                'max:255',
                // ✅ Validation unique : empêcher les doublons d'emails pour le même événement
                Rule::unique('registrations', 'guest_email')
                    ->where(fn ($query) => $query->where('event_id', $event->id))
                    ->where(fn ($query) => $query->where('status', '!=', 'CANCELLED')), // Ignorer les inscriptions annulées
            ],
            'is_attending' => ['nullable', 'boolean'],
            'dietary_info' => ['nullable', 'string', 'max:2000'],
            'invite_token' => $isPrivate
                ? [
                    'required',
                    'string',
                    Rule::exists('registrations', 'invite_token')
                        ->where(fn ($query) => $query->where('event_id', $event->id)->where('status', 'INVITED')),
                ]
                : ['nullable', 'string'],

            'guests_count' => ['required', 'integer', 'min:0', 'max:' . $maxGuests],
            'guests' => $isPrivate ? ['prohibited'] : ['array'],
            'guests.*.name' => ['nullable', 'string', 'max:255'],
            'guests.*.email' => ['nullable', 'email', 'max:255'],
        ];
    }
}
