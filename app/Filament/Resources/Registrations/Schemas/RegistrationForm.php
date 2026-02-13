<?php

namespace App\Filament\Resources\Registrations\Schemas;

use Filament\Forms;
use Filament\Schemas\Schema;

class RegistrationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\Select::make('event_id')
                ->label('Événement')
                ->relationship('event', 'title', function ($query) {
                    $user = auth()->user();

                    if (! $user || $user->isAdmin()) {
                        return $query;
                    }

                    return $query->where('user_id', $user->id);
                })
                ->searchable()
                ->required(),

            Forms\Components\TextInput::make('guest_name')
                ->label('Nom')
                ->required(),

            Forms\Components\TextInput::make('guest_email')
                ->label('Email')
                ->email()
                ->required(),

            Forms\Components\Select::make('status')
                ->label('Statut')
                ->options([
                    'INVITED' => 'INVITED',
                    'REGISTERED' => 'REGISTERED',
                    'CANCELLED' => 'CANCELLED',
                ])
                ->required(),

            Forms\Components\Toggle::make('is_attending')
                ->label('Présent'),

            Forms\Components\Textarea::make('dietary_info')
                ->label('Contraintes alimentaires')
                ->rows(3),

            Forms\Components\DateTimePicker::make('registered_at')
                ->label('Inscrit le'),
        ]);
    }
}
