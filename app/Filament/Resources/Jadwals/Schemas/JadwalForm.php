<?php

namespace App\Filament\Resources\Jadwals\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

class JadwalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('film_id')
                    ->required()
                    ->numeric(),
                TextInput::make('studio_id')
                    ->required()
                    ->numeric(),
                TextInput::make('harga_id')
                    ->required()
                    ->numeric(),
                DatePicker::make('tanggal')
                    ->required(),
                TimePicker::make('jamtayang')
                    ->required(),
            ]);
    }
}
