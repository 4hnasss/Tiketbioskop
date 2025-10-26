<?php

namespace App\Filament\Resources\Hargas;

use App\Filament\Resources\Hargas\Pages\CreateHarga;
use App\Filament\Resources\Hargas\Pages\EditHarga;
use App\Filament\Resources\Hargas\Pages\ListHargas;
use App\Filament\Resources\Hargas\Schemas\HargaForm;
use App\Filament\Resources\Hargas\Tables\HargasTable;
use App\Models\Harga;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class HargaResource extends Resource
{
    protected static ?string $model = Harga::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'harga';

    public static function form(Schema $schema): Schema
    {
        return HargaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HargasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListHargas::route('/'),
            'create' => CreateHarga::route('/create'),
            'edit' => EditHarga::route('/{record}/edit'),
        ];
    }
}
