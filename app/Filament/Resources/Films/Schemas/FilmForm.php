<?php

namespace App\Filament\Resources\Films\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class FilmForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('judul'),
                TextInput::make('genre'),
                TextInput::make('durasi')
                    ->numeric(),
                Textarea::make('deskripsi')
                    ->columnSpanFull(),
                Select::make('status')
                    ->options(['upcomming' => 'Upcomming', 'playnow' => 'Playnow'])
                    ->default('upcomming'),
                TextInput::make('poster'),
                DatePicker::make('tanggalmulai'),
                DatePicker::make('tanggalselesai'),
            ]);
    }
}
