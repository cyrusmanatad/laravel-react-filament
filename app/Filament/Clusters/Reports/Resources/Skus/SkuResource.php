<?php

namespace App\Filament\Clusters\Reports\Resources\Skus;

use App\Filament\Clusters\Reports\ReportsCluster;
use App\Filament\Clusters\Reports\Resources\Skus\Pages\CreateSku;
use App\Filament\Clusters\Reports\Resources\Skus\Pages\EditSku;
use App\Filament\Clusters\Reports\Resources\Skus\Pages\ListSkus;
use App\Filament\Clusters\Reports\Resources\Skus\Pages\ViewSku;
use App\Filament\Clusters\Reports\Resources\Skus\Schemas\SkuForm;
use App\Filament\Clusters\Reports\Resources\Skus\Schemas\SkuInfolist;
use App\Filament\Clusters\Reports\Resources\Skus\Tables\SkusTable;
use App\Models\Sku;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SkuResource extends Resource
{
    protected static ?string $model = Sku::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ShoppingBag;

    protected static ?string $cluster = ReportsCluster::class;

    protected static ?string $recordTitleAttribute = 'sku_desc';

    public static function form(Schema $schema): Schema
    {
        return SkuForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SkuInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SkusTable::configure($table);
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
            'index' => ListSkus::route('/'),
            'create' => CreateSku::route('/create'),
            'view' => ViewSku::route('/{record}'),
            'edit' => EditSku::route('/{record}/edit'),
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
