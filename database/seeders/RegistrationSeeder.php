<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegistrationSeeder extends Seeder
{
    public function run(): void
    {
        // USERS (roles autorisés: admin / employee)
        $admin = User::firstOrCreate(
            ['email' => 'admin@myevents.test'],
            [
                'name' => 'Admin MyEvents',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        $employee = User::firstOrCreate(
            ['email' => 'employee@myevents.test'],
            [
                'name' => 'Employee MyEvents',
                'password' => Hash::make('password'),
                'role' => 'employee',
            ]
        );

        // EVENT (champs compatibles avec ton modèle Event)
        $event = Event::firstOrCreate(
            ['slug' => 'soiree-entreprise-2026'],
            [
                'user_id' => $employee->id,
                'title' => "Soirée d'entreprise 2026",
                'description' => "Une soirée networking avec buffet.\nTenue correcte exigée.",
                'date_start' => now()->addDays(14)->setTime(19, 0),
                'date_end' => now()->addDays(14)->setTime(23, 0),
                'location' => 'Paris - Le Grand Salon',
                'cover_image' => null,
                'max_participants' => 120,
                'max_guests_per_registration' => 2,
                'is_public' => true,
            ]
        );

        // REGISTRATIONS : 1 principal + 2 accompagnants
        $principal = Registration::create([
            'event_id' => $event->id,
            'parent_registration_id' => null,
            'invite_token' => (string) Str::uuid(),
            'guest_name' => 'Marie Dupont',
            'guest_email' => 'marie.dupont@eventpro-solutions.fr',
            'status' => 'REGISTERED',
            'is_attending' => true,
            'dietary_info' => 'Végétarienne',
            'registered_at' => now()->subDays(2),
        ]);

        Registration::create([
            'event_id' => $event->id,
            'parent_registration_id' => $principal->id,
            'invite_token' => (string) Str::uuid(),
            'guest_name' => 'Sophie Martin',
            'guest_email' => 'sophie.martin@eventpro-solutions.fr',
            'status' => 'REGISTERED',
            'is_attending' => true,
            'registered_at' => now()->subDays(2),
        ]);

        Registration::create([
            'event_id' => $event->id,
            'parent_registration_id' => $principal->id,
            'invite_token' => (string) Str::uuid(),
            'guest_name' => 'Lucas Bernard',
            'guest_email' => 'lucas.bernard@eventpro-solutions.fr',
            'status' => 'REGISTERED',
            'is_attending' => true,
            'registered_at' => now()->subDays(2),
        ]);
    }
}
