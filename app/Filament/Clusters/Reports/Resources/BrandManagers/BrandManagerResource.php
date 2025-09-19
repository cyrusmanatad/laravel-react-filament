<?php

namespace App\Filament\Clusters\Reports\Resources\BrandManagers;

use App\Filament\Clusters\Reports\ReportsCluster;
use App\Filament\Clusters\Reports\Resources\BrandManagers\Pages\CreateBrandManager;
use App\Filament\Clusters\Reports\Resources\BrandManagers\Pages\EditBrandManager;
use App\Filament\Clusters\Reports\Resources\BrandManagers\Pages\ListBrandManagers;
use App\Filament\Clusters\Reports\Resources\BrandManagers\Pages\ViewBrandManager;
use App\Filament\Clusters\Reports\Resources\BrandManagers\Schemas\BrandManagerForm;
use App\Filament\Clusters\Reports\Resources\BrandManagers\Schemas\BrandManagerInfolist;
use App\Filament\Clusters\Reports\Resources\BrandManagers\Tables\BrandManagersTable;
use App\Models\BrandManager;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BrandManagerResource extends Resource
{
    protected static ?string $model = BrandManager::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::UserGroup;

    protected static ?string $cluster = ReportsCluster::class;

    public static function form(Schema $schema): Schema
    {
        return BrandManagerForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BrandManagerInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BrandManagersTable::configure($table);
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
            'index' => ListBrandManagers::route('/'),
            'create' => CreateBrandManager::route('/create'),
            'view' => ViewBrandManager::route('/{record}'),
            'edit' => EditBrandManager::route('/{record}/edit'),
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
