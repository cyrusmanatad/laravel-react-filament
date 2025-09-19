<?php

namespace App\Filament\Clusters\Reports\Resources\UtsSkus;

use App\Filament\Clusters\Reports\ReportsCluster;
use App\Filament\Clusters\Reports\Resources\UtsSkus\Pages\CreateUtsSku;
use App\Filament\Clusters\Reports\Resources\UtsSkus\Pages\EditUtsSku;
use App\Filament\Clusters\Reports\Resources\UtsSkus\Pages\ListUtsSkus;
use App\Filament\Clusters\Reports\Resources\UtsSkus\Pages\ViewUtsSku;
use App\Filament\Clusters\Reports\Resources\UtsSkus\Schemas\UtsSkuForm;
use App\Filament\Clusters\Reports\Resources\UtsSkus\Schemas\UtsSkuInfolist;
use App\Filament\Clusters\Reports\Resources\UtsSkus\Tables\UtsSkusTable;
use App\Models\UtsSku;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UtsSkuResource extends Resource
{
    protected static ?string $model = UtsSku::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingBag;

    protected static ?string $cluster = ReportsCluster::class;

    public static function form(Schema $schema): Schema
    {
        return UtsSkuForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return UtsSkuInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UtsSkusTable::configure($table);
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
            'index' => ListUtsSkus::route('/'),
            'create' => CreateUtsSku::route('/create'),
            'view' => ViewUtsSku::route('/{record}'),
            'edit' => EditUtsSku::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
