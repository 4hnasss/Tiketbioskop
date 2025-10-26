<?php

namespace App\Filament\Resources\Kursis\Pages;

use App\Filament\Resources\Kursis\KursiResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKursis extends ListRecords
{
    protected static string $resource = KursiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
