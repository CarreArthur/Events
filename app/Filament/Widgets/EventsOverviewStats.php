<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EventsOverviewStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $user = auth()->user();
        $now = now();

        $eventsQuery = Event::query();

        if ($user && ! $user->isAdmin()) {
            $eventsQuery->where('user_id', $user->id);
        }

        $totalEvents = (clone $eventsQuery)->count();
        $upcomingEvents = (clone $eventsQuery)
            ->where('date_start', '>=', $now)
            ->count();
        $pastEvents = (clone $eventsQuery)
            ->where(function ($query) use ($now) {
                $query
                    ->where('date_end', '<', $now)
                    ->orWhere(function ($query) use ($now) {
                        $query->whereNull('date_end')->where('date_start', '<', $now);
                    });
            })
            ->count();
        $publicEvents = (clone $eventsQuery)->where('is_public', true)->count();
        $privateEvents = (clone $eventsQuery)->where('is_public', false)->count();

        return [
            Stat::make('Total événements', $totalEvents)
                ->color('primary'),
            Stat::make('À venir', $upcomingEvents)
                ->color('info'),
            Stat::make('Terminés', $pastEvents)
                ->color('gray'),
            Stat::make('Publics', $publicEvents)
                ->color('success'),
            Stat::make('Privés', $privateEvents)
                ->color('warning'),
        ];
    }
}
