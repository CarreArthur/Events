<?php

namespace App\Filament\Resources\Reviews;

use App\Filament\Resources\Reviews\Pages\ListReviews;
use App\Models\Review;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static ?string $navigationLabel = 'Avis';

    protected static ?string $pluralModelLabel = 'Avis';

    protected static ?string $modelLabel = 'Avis';

    protected static ?int $navigationSort = 5;

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->with(['event', 'registration']);
        $user = auth()->user();

        if (! $user || $user->isAdmin()) {
            return $query;
        }

        return $query->whereHas('event', fn (Builder $eventQuery) => $eventQuery->where('user_id', $user->id));
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('event.title')
                    ->label('Événement')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                TextColumn::make('registration.guest_name')
                    ->label('Participant')
                    ->placeholder('—')
                    ->wrap(),
                TextColumn::make('registration.guest_email')
                    ->label('Email')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('rating')
                    ->label('Note')
                    ->badge()
                    ->color(fn (int $state): string => match ($state) {
                        5, 4 => 'success',
                        3 => 'warning',
                        2, 1 => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('comment')
                    ->label('Commentaire')
                    ->placeholder('—')
                    ->lineClamp(3)
                    ->wrap(),
                TextColumn::make('created_at')
                    ->label('Laissé le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('event_id')
                    ->label('Événement')
                    ->relationship('event', 'title')
                    ->searchable(),
                Tables\Filters\SelectFilter::make('rating')
                    ->label('Note')
                    ->options([
                        5 => '5',
                        4 => '4',
                        3 => '3',
                        2 => '2',
                        1 => '1',
                    ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListReviews::route('/'),
        ];
    }
}
