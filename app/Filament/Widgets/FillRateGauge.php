<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use App\Models\Registration;
use Filament\Widgets\ChartWidget;

class FillRateGauge extends ChartWidget
{
    protected string $color = 'success';

    protected ?string $heading = 'Taux de remplissage';

    protected ?string $maxHeight = '220px';

    public static function canView(): bool
    {
        $user = auth()->user();

        return $user && $user->isAdmin();
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getData(): array
    {
        $totalCapacity = Event::query()
            ->whereNotNull('max_participants')
            ->sum('max_participants');

        $usedSeats = Registration::query()
            ->where('status', 'REGISTERED')
            ->where('is_attending', true)
            ->count();

        $remainingSeats = $totalCapacity > 0
            ? max(0, $totalCapacity - $usedSeats)
            : 0;

        $data = $totalCapacity > 0 ? [$usedSeats, $remainingSeats] : [0, 1];

        return [
            'labels' => ['Occupees', 'Restantes'],
            'datasets' => [
                [
                    'label' => 'Remplissage',
                    'data' => $data,
                    'backgroundColor' => ['#22c55e', '#e5e7eb'],
                    'borderWidth' => 0,
                ],
            ],
        ];
    }

    public function getDescription(): ?string
    {
        $totalCapacity = Event::query()
            ->whereNotNull('max_participants')
            ->sum('max_participants');

        if ($totalCapacity === 0) {
            return 'Aucune capacite definie';
        }

        $usedSeats = Registration::query()
            ->where('status', 'REGISTERED')
            ->where('is_attending', true)
            ->count();

        $rate = round(($usedSeats / $totalCapacity) * 100);

        return $rate . '% - ' . $usedSeats . '/' . $totalCapacity;
    }

    protected function getOptions(): array
    {
        return [
            'cutout' => '70%',
            'circumference' => 180,
            'rotation' => 270,
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
        ];
    }
}
