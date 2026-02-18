<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CreateTestUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer un administrateur
        User::firstOrCreate(
            ['email' => 'admin@eventpro.fr'],
            [
                'name' => 'Marie Dupont (Admin)',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        // Créer un chef de projet (employee)
        User::firstOrCreate(
            ['email' => 'sophie@eventpro.fr'],
            [
                'name' => 'Sophie Martin (Chef de Projet)',
                'password' => Hash::make('password123'),
                'role' => 'employee',
            ]
        );

        // Créer un second chef de projet
        User::firstOrCreate(
            ['email' => 'jean@eventpro.fr'],
            [
                'name' => 'Jean Dupuis (Chef de Projet)',
                'password' => Hash::make('password123'),
                'role' => 'employee',
            ]
        );

        $this->command->info('✅ Utilisateurs de test créés avec succès!');
        $this->command->line('');
        $this->command->info('📧 Identifiants:');
        $this->command->line('Admin:     admin@eventpro.fr / password123');
        $this->command->line('Chef 1:    sophie@eventpro.fr / password123');
        $this->command->line('Chef 2:    jean@eventpro.fr / password123');
    }
}
