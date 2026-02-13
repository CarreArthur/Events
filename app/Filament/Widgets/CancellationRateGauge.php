<?php

namespace App\Filament\Widgets;

use App\Models\Registration;
use Filament\Widgets\ChartWidget;

class CancellationRateGauge extends ChartWidget
{
    protected string $color = 'warning';

    protected ?string $heading = 'Taux d\'annulation';

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
        $cancelled = Registration::query()
            ->where('status', 'CANCELLED')
            ->count();

        $registered = Registration::query()
            ->where('status', 'REGISTERED')
            ->count();

        $total = $cancelled + $registered;

        $data = $total > 0 ? [$cancelled, $registered] : [0, 1];

        return [
            'labels' => ['Annulees', 'Actives'],
            'datasets' => [
                [
                    'label' => 'Annulations',
                    'data' => $data,
                    'backgroundColor' => ['#ef4444', '#e5e7eb'],
                    'borderWidth' => 0,
                ],
            ],
        ];
    }

    public function getDescription(): ?string
    {
        $cancelled = Registration::query()
            ->where('status', 'CANCELLED')
            ->count();

        $registered = Registration::query()
            ->where('status', 'REGISTERED')
            ->count();

        $total = $cancelled + $registered;

        if ($total === 0) {
            return 'Aucune inscription';
        }

        $rate = round(($cancelled / $total) * 100);

        return $rate . '% - ' . $cancelled . '/' . $total;
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
