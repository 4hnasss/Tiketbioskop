<?php

namespace App\Filament\Resources\Kursis\Pages;

use App\Filament\Resources\Kursis\KursiResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditKursi extends EditRecord
{
    protected static string $resource = KursiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
