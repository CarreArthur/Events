<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Event;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('+1 days', '+1 month');
        $end = (clone $start);
        $end->modify('+' . $this->faker->numberBetween(2, 8) . ' hours');

        return [
            'user_id' => \App\Models\User::factory(),
            'title' => $this->faker->sentence(3),
            'slug' => $this->faker->unique()->slug(),
            'description' => $this->faker->paragraph(),
            'date_start' => $start,
            'date_end' => $end,
            'location' => $this->faker->address(),
            'cover_image' => null,
            'max_participants' => $this->faker->numberBetween(10, 100),
            'max_guests_per_registration' => $this->faker->numberBetween(0, 2),
            'is_public' => $this->faker->boolean(80),
        ];
    }

    public function public(): static
    {
        return $this->state(fn () => ['is_public' => true]);
    }

    public function private(): static
    {
        return $this->state(fn () => ['is_public' => false]);
    }
}

/**
 * @property array<string, string> $casts
 */
class User
{
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isEmployee(): bool
    {
        return $this->role === 'employee';
    }
}
