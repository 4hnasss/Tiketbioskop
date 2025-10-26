<?php

namespace App\Filament\Resources\Hargas\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class HargaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('jenis_hari')
                    ->options(['weekday' => 'Weekday', 'weekend' => 'Weekend'])
                    ->default('weekday')
                    ->required(),
                TextInput::make('harga')
                    ->numeric(),
            ]);
    }
}
