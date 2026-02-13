<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use App\Models\Registration;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RegistrationsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $user = auth()->user();

        $eventsQuery = Event::query();
        $registrationsQuery = Registration::query()->where('status', 'REGISTERED');

        if ($user && ! $user->isAdmin()) {
            $eventsQuery->where('user_id', $user->id);
            $registrationsQuery->whereHas('event', fn ($query) => $query->where('user_id', $user->id));
        }

        $totalEvents = (clone $eventsQuery)->count();
        $totalRegistrations = (clone $registrationsQuery)->count();
        $usedSeats = (clone $registrationsQuery)->where('is_attending', true)->count();
        $totalCapacity = (clone $eventsQuery)->whereNotNull('max_participants')->sum('max_participants');

        $fillRate = $totalCapacity > 0
            ? round(($usedSeats / $totalCapacity) * 100)
            : null;

        return [
            Stat::make('Evenements', $totalEvents),
            Stat::make('Inscriptions', $totalRegistrations),
            Stat::make('Taux de remplissage', $fillRate === null ? '-' : $fillRate . ' %'),
        ];
    }
}
