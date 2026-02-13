<?php

namespace App\Filament\Resources\Events\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Chef de projet')
                    ->searchable()
                    ->sortable()
                    ->visible(fn () => auth()->user()?->isAdmin() ?? false),
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('date_start')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('date_end')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('location')
                    ->searchable(),
                ImageColumn::make('cover_image'),
                TextColumn::make('max_participants')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('max_guests_per_registration')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_public')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
