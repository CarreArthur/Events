<?php

namespace App\Filament\Resources\Registrations\Pages;

use App\Models\Event;
use App\Models\Registration;
use Filament\Actions\Action;
use App\Filament\Resources\Registrations\RegistrationResource;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ListRecords;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListRegistrations extends ListRecords
{
    protected static string $resource = RegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            Action::make('exportCsv')
                ->label('Exporter CSV')
                ->icon('heroicon-o-arrow-down-tray')
                ->form([
                    Select::make('event_id')
                        ->label('Événement')
                        ->searchable()
                        ->options(function () {
                            $user = auth()->user();
                            $query = Event::query()->orderBy('title');

                            if ($user && ! $user->isAdmin()) {
                                $query->where('user_id', $user->id);
                            }

                            return $query->pluck('title', 'id')->all();
                        })
                        ->required(),
                ])
                ->action(function (array $data): StreamedResponse {
                    $user = auth()->user();
                    $query = Registration::query()->with('event');

                    if ($user && ! $user->isAdmin()) {
                        $query->whereHas('event', fn ($eventQuery) => $eventQuery->where('user_id', $user->id));
                    }

                    $query->where('event_id', $data['event_id']);
                    $rows = $query->orderBy('registered_at', 'desc')->get();
                    $event = Event::find($data['event_id']);
                    $filename = 'inscriptions-' . ($event?->slug ?? 'event') . '.csv';

                    return response()->streamDownload(function () use ($rows) {
                        $output = fopen('php://output', 'w');

                        fputcsv($output, [
                            'Evenement',
                            'Nom',
                            'Email',
                            'Statut',
                            'Present',
                            'Inscrit le',
                            'Contraintes',
                        ], ';');

                        foreach ($rows as $row) {
                            fputcsv($output, [
                                $row->event?->title,
                                $row->guest_name,
                                $row->guest_email,
                                $row->status,
                                $row->is_attending ? 'Oui' : 'Non',
                                optional($row->registered_at)->format('d/m/Y H:i'),
                                $row->dietary_info,
                            ], ';');
                        }

                        fclose($output);
                    }, $filename, ['Content-Type' => 'text/csv']);
                }),
        ];
    }
}
