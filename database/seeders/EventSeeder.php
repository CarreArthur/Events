<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        // 1 event PUBLIC garanti (pour tester /register)
        Event::factory()->create([
            'title' => "Soirée d'entreprise 2026",
            'slug' => 'soiree-entreprise-2026',
            'is_public' => true,
            'max_guests_per_registration' => 2,
            'max_participants' => 120,
        ]);

        // 1 event PRIVÉ garanti
        Event::factory()->create([
            'title' => "Lancement produit (privé)",
            'slug' => 'lancement-produit-prive',
            'is_public' => false,
            'max_guests_per_registration' => 0,
            'max_participants' => 40,
        ]);

        // le reste en aléatoire (ex: 48)
        Event::factory()->count(48)->create();
    }
}
