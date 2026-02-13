<?php

namespace App\Filament\Resources\Registrations\Tables;

use Filament\Tables;
use Filament\Tables\Table;

class RegistrationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('event.title')
                    ->label('Événement')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('guest_name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('guest_email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->sortable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_attending')
                    ->label('Présent')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('registered_at')
                    ->label('Inscrit le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('event_id')
                    ->label('Événement')
                    ->relationship('event', 'title')
                    ->searchable(),

                Tables\Filters\SelectFilter::make('status')
                    ->label('Statut')
                    ->options([
                        'INVITED' => 'INVITED',
                        'REGISTERED' => 'REGISTERED',
                        'CANCELLED' => 'CANCELLED',
                    ]),

                Tables\Filters\TernaryFilter::make('is_attending')
                    ->label('Présent'),
            ])
            ->defaultSort('registered_at', 'desc');
    }
}
