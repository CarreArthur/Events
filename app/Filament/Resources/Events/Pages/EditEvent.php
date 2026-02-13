<?php

namespace App\Filament\Resources\Events\Pages;

use App\Filament\Resources\Events\EventResource;
use App\Models\Registration;
use App\Notifications\EventInvitationNotification;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Str;

class EditEvent extends EditRecord
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('invite')
                ->label('Envoyer une invitation')
                ->icon('heroicon-o-paper-airplane')
                ->visible(fn () => auth()->user()?->isAdmin() && ! $this->record->is_public)
                ->form([
                    TextInput::make('guest_email')
                        ->label('Email de l\'invite')
                        ->email()
                        ->required(),
                    TextInput::make('guest_name')
                        ->label('Nom (optionnel)')
                        ->maxLength(255),
                ])
                ->action(function (array $data): void {
                    $invitation = Registration::create([
                        'event_id' => $this->record->id,
                        'parent_registration_id' => null,
                        'invite_token' => (string) Str::uuid(),
                        'guest_name' => $data['guest_name'] ?? null,
                        'guest_email' => $data['guest_email'],
                        'status' => 'INVITED',
                        'is_attending' => true,
                    ]);

                    $invitation->notify(new EventInvitationNotification($invitation));
                }),
            DeleteAction::make(),
        ];
    }
}
