<?php

namespace App\Filament\Resources\Events\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Chef de projet')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required()
                    ->visible(fn () => auth()->user()?->isAdmin() ?? false),
                Hidden::make('user_id')
                    ->default(fn () => auth()->id())
                    ->visible(fn () => ! (auth()->user()?->isAdmin() ?? false)),
                TextInput::make('title')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                DateTimePicker::make('date_start')
                    ->required(),
                DateTimePicker::make('date_end')
                    ->required(),
                TextInput::make('location')
                    ->required(),
                FileUpload::make('cover_image')
                    ->image(),
                TextInput::make('max_participants')
                    ->numeric(),
                TextInput::make('max_guests_per_registration')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_public')
                    ->required(),
            ]);
    }
}
