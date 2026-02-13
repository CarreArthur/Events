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
        $now = now();

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

        $stats = [
            Stat::make('Evenements', $totalEvents)
                ->color('info'),
            Stat::make('Inscriptions', $totalRegistrations)
                ->color('primary'),
        ];

        if ($user && $user->isAdmin()) {
            $upcomingEvents = (clone $eventsQuery)->where('date_start', '>=', $now)->count();

            $cancelledRegistrations = Registration::query()->where('status', 'CANCELLED')->count();

            $fullEvents = (clone $eventsQuery)
                ->whereNotNull('max_participants')
                ->whereRaw(
                    "(select count(*) from registrations where events.id = registrations.event_id and status = 'REGISTERED' and is_attending = 1) >= max_participants"
                )
                ->count();

            $remainingSeats = $totalCapacity > 0
                ? max(0, $totalCapacity - $usedSeats)
                : null;

            $fullEventsColor = $fullEvents > 0 ? 'warning' : 'success';
            $remainingSeatsColor = match (true) {
                $remainingSeats === null => 'gray',
                $remainingSeats === 0 => 'danger',
                $remainingSeats <= 10 => 'warning',
                default => 'success',
            };

            $stats = array_merge($stats, [
                Stat::make('Evenements a venir', $upcomingEvents)
                    ->color('info'),
                Stat::make('Evenements complets', $fullEvents)
                    ->color($fullEventsColor),
                Stat::make('Places restantes', $remainingSeats === null ? '-' : $remainingSeats)
                    ->color($remainingSeatsColor),
                Stat::make('Annulations', $cancelledRegistrations)
                    ->color($cancelledRegistrations > 0 ? 'warning' : 'success'),
            ]);
        }

        return $stats;
    }
}
