<?php

namespace App\Filament\Resources\Kursis\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class KursiInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
                TextEntry::make('jadwal_id')
                    ->numeric(),
                TextEntry::make('studio_id')
                    ->numeric(),
                TextEntry::make('nomorkursi'),
                TextEntry::make('status'),
            ]);
    }
}
