<?php

namespace App\Filament\Resources\Invitations;

use App\Models\Registration;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Invitations\Pages\ListInvitations;
use App\Filament\Resources\Invitations\Pages\ViewInvitation;

class InvitationResource extends Resource
{
    protected static ?string $model = Registration::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-paper-airplane';

    protected static ?string $navigationLabel = 'Invitations privées';

    protected static ?string $pluralModelLabel = 'Invitation';

    protected static ?int $navigationSort = 3;

    public static function getRecordRouteKeyName(): string
    {
        return 'id';
    }

    /**
     * Filtrer pour afficher seulement les invitations (status = INVITED)
     */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->where('status', 'INVITED')
            ->whereHas('event', fn ($q) => $q->where('is_public', false)); // Seulement les privés

        // Si employee : voir seulement ses propres invitations
        $user = auth()->user();
        if ($user && !$user->isAdmin()) {
            $query->whereHas('event', fn ($q) => $q->where('user_id', $user->id));
        }

        return $query;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('event.title')
                    ->label('Événement')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('guest_email')
                    ->label('Email invité')
                    ->searchable()
                    ->copyable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('guest_name')
                    ->label('Nom (optionnel)')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('invite_token')
                    ->label('Token d\'invitation')
                    ->copyable()
                    ->wrap()
                    ->fontFamily('mono')
                    ->size('xs'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créée le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->actions([
                // ✨ ACTION : Copier le lien d'invitation complet
                Action::make('copy_link')
                    ->label('Copier le lien')
                    ->icon('heroicon-o-link')
                    ->color('info')
                    ->form([
                        TextInput::make('invite_link')
                            ->label('Lien d\'invitation')
                            ->default(fn (Registration $record): string => route('registrations.invite', $record->invite_token))
                            ->disabled()
                            ->dehydrated(false)
                            ->copyable(copyMessage: 'Lien copié !'),
                    ])
                    ->modalHeading('Lien d\'invitation')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Fermer')
                    ->tooltip('Copier le lien d\'invitation à partager'),

                // ✨ ACTION : Voir le lien (full URL)
                ViewAction::make()
                    ->label('Voir détails')
                    ->icon('heroicon-o-eye'),

                // ✨ ACTION : Renvoyer l'email (si nécessaire)
                Action::make('resend_email')
                    ->label('Renvoyer l\'email')
                    ->icon('heroicon-o-envelope')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->action(function (Registration $record) {
                        $record->notify(new \App\Notifications\EventInvitationNotification($record));
                        $record->forceFill(['email_sent_at' => now()])->save();
                    })
                    ->after(fn () => \Filament\Notifications\Notification::make()
                        ->success()
                        ->title('Email renvoyé')
                        ->send()),

                // ✨ ACTION : Marquer comme confirmée (pour tester)
                Action::make('mark_confirmed')
                    ->label('Marquer comme confirmée')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (Registration $record) {
                        $record->update([
                            'status' => 'REGISTERED',
                            'registered_at' => now(),
                        ]);
                    })
                    ->after(fn () => \Filament\Notifications\Notification::make()
                        ->success()
                        ->title('Invitation acceptée')
                        ->send()),
            ])
            ->filters([
                // Filtrer par événement
                Tables\Filters\SelectFilter::make('event_id')
                    ->label('Événement')
                    ->relationship('event', 'title')
                    ->searchable(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function canCreate(): bool
    {
        return false; // Les invitations se créent via l'EditEvent
    }

    public static function canEdit($record): bool
    {
        return false; // Les invitations ne se modifient pas
    }

    public static function canDelete($record): bool
    {
        return false; // Pas de suppression
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInvitations::route('/'),
            'view' => ViewInvitation::route('/{record}'),
        ];
    }
}
