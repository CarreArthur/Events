<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Support\Facades\Artisan;

class Dashboard extends BaseDashboard
{
    protected function getHeaderActions(): array
    {
        return [
            Action::make('send_review_requests')
                ->label('Envoyer les demandes d\'avis')
                ->icon('heroicon-o-paper-airplane')
                ->visible(fn (): bool => auth()->user()?->isAdmin() ?? false)
                ->requiresConfirmation()
                ->modalHeading('Envoyer les demandes d\'avis')
                ->modalDescription('Un email sera envoye aux participants eligibles.')
                ->modalSubmitActionLabel('Envoyer')
                ->action(function (): void {
                    $exitCode = Artisan::call('reviews:send-requests');

                    if ($exitCode === 0) {
                        Notification::make()
                            ->success()
                            ->title('Demandes d\'avis envoyees')
                            ->send();

                        return;
                    }

                    Notification::make()
                        ->danger()
                        ->title('Echec de l\'envoi des demandes')
                        ->send();
                }),
        ];
    }
}
