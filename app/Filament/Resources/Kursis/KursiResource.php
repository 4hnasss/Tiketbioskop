<?php

namespace App\Filament\Resources\Kursis;

use App\Filament\Resources\Kursis\Pages\CreateKursi;
use App\Filament\Resources\Kursis\Pages\EditKursi;
use App\Filament\Resources\Kursis\Pages\ListKursis;
use App\Filament\Resources\Kursis\Pages\ViewKursi;
use App\Filament\Resources\Kursis\Schemas\KursiForm;
use App\Filament\Resources\Kursis\Schemas\KursiInfolist;
use App\Filament\Resources\Kursis\Tables\KursisTable;
use App\Models\Kursi;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class KursiResource extends Resource
{
    protected static ?string $model = Kursi::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'nomorkursi';

    public static function form(Schema $schema): Schema
    {
        return KursiForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return KursiInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KursisTable::configure($table);
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
            'index' => ListKursis::route('/'),
            'create' => CreateKursi::route('/create'),
            'view' => ViewKursi::route('/{record}'),
            'edit' => EditKursi::route('/{record}/edit'),
        ];
    }
}
