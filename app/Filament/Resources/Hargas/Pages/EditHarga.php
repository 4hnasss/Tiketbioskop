<?php

namespace App\Filament\Resources\Hargas\Pages;

use App\Filament\Resources\Hargas\HargaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHarga extends EditRecord
{
    protected static string $resource = HargaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
