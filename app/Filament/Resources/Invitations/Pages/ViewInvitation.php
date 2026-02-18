<?php

namespace App\Filament\Resources\Invitations\Pages;

use App\Filament\Resources\Invitations\InvitationResource;
use Filament\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ViewRecord;

class ViewInvitation extends ViewRecord
{
    protected static string $resource = InvitationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('copy_full_link')
                ->label('Copier le lien complet')
                ->icon('heroicon-o-clipboard')
                ->color('success')
                ->form([
                    TextInput::make('invite_link')
                        ->label('Lien d\'invitation')
                        ->default(fn (): string => route('registrations.invite', $this->record->invite_token))
                        ->disabled()
                        ->dehydrated(false)
                        ->copyable(copyMessage: 'Lien copié !'),
                ])
                ->modalHeading('Lien d\'invitation')
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Fermer'),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            // Afficher les infos principales
        ];
    }
}

