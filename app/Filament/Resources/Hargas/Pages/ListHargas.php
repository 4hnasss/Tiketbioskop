<?php

namespace App\Filament\Resources\Hargas\Pages;

use App\Filament\Resources\Hargas\HargaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHargas extends ListRecords
{
    protected static string $resource = HargaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
