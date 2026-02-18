<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class UpcomingEventsTable extends TableWidget
{
    protected int | string | array $columnSpan = 'full';

    protected function getTableQuery(): Builder
    {
        $user = auth()->user();

        $query = Event::query()
            ->withCount([
                'registrations as registrations_count' => fn (Builder $query) => $query
                    ->where('status', 'REGISTERED'),
            ])
            ->where('date_start', '>=', now())
            ->orderBy('date_start');

        if ($user && ! $user->isAdmin()) {
            $query->where('user_id', $user->id);
        }

        return $query;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->defaultPaginationPageOption(5)
            ->columns([
                TextColumn::make('title')
                    ->label('Événement')
                    ->wrap()
                    ->searchable(),
                TextColumn::make('date_start')
                    ->label('Début')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('location')
                    ->label('Lieu')
                    ->wrap(),
                BadgeColumn::make('is_public')
                    ->label('Visibilité')
                    ->colors([
                        'success' => true,
                        'warning' => false,
                    ])
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Public' : 'Privé'),
                TextColumn::make('registrations_count')
                    ->label('Inscrits')
                    ->sortable(),
            ]);
    }
}
