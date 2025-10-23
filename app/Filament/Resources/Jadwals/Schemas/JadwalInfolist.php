<?php

namespace App\Filament\Resources\Jadwals\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class JadwalInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('film_id')
                    ->numeric(),
                TextEntry::make('studio_id')
                    ->numeric(),
                TextEntry::make('harga_id')
                    ->numeric(),
                TextEntry::make('tanggal')
                    ->date(),
                TextEntry::make('jamtayang')
                    ->time(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
