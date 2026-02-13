<?php

namespace App\Filament\Widgets;

use App\Models\Registration;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestRegistrations extends TableWidget
{
    protected int | string | array $columnSpan = 'full';

    protected function getTableQuery(): Builder
    {
        $user = auth()->user();

        $query = Registration::query()
            ->with('event')
            ->where('status', 'REGISTERED')
            ->orderByDesc('registered_at');

        if ($user && ! $user->isAdmin()) {
            $query->whereHas('event', fn (Builder $eventQuery) => $eventQuery->where('user_id', $user->id));
        }

        return $query;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->defaultPaginationPageOption(5)
            ->columns([
                TextColumn::make('event.title')
                    ->label('Evenement')
                    ->wrap(),
                TextColumn::make('guest_name')
                    ->label('Nom')
                    ->wrap(),
                TextColumn::make('guest_email')
                    ->label('Email')
                    ->wrap(),
                TextColumn::make('registered_at')
                    ->label('Inscrit le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ]);
    }
}
