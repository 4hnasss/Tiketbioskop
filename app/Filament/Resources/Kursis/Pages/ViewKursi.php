<?php

namespace App\Filament\Resources\Kursis\Pages;

use App\Filament\Resources\Kursis\KursiResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewKursi extends ViewRecord
{
    protected static string $resource = KursiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
