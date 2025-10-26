<?php

namespace App\Filament\Resources\Kursis\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class KursiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('jadwal_id')
                    ->required()
                    ->numeric(),
                TextInput::make('studio_id')
                    ->required()
                    ->numeric(),
                TextInput::make('nomorkursi')
                    ->required(),
                Select::make('status')
                    ->options([
            'tersedia' => 'Tersedia',
            'tidaktersedia' => 'Tidaktersedia',
            'dipesan' => 'Dipesan',
            'terjual' => 'Terjual',
        ])
                    ->default('tersedia')
                    ->required(),
            ]);
    }
}
