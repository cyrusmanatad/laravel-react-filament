<?php

namespace App\Filament\Clusters\Reports\Resources\BranchPlants;

use App\Filament\Clusters\Reports\ReportsCluster;
use App\Filament\Clusters\Reports\Resources\BranchPlants\Pages\CreateBranchPlant;
use App\Filament\Clusters\Reports\Resources\BranchPlants\Pages\EditBranchPlant;
use App\Filament\Clusters\Reports\Resources\BranchPlants\Pages\ListBranchPlants;
use App\Filament\Clusters\Reports\Resources\BranchPlants\Pages\ViewBranchPlant;
use App\Filament\Clusters\Reports\Resources\BranchPlants\Schemas\BranchPlantForm;
use App\Filament\Clusters\Reports\Resources\BranchPlants\Schemas\BranchPlantInfolist;
use App\Filament\Clusters\Reports\Resources\BranchPlants\Tables\BranchPlantsTable;
use App\Models\BranchPlant;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BranchPlantResource extends Resource
{
    protected static ?string $model = BranchPlant::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::BuildingStorefront;

    protected static ?string $cluster = ReportsCluster::class;

    protected static ?string $recordTitleAttribute = 'branch_desc';

    public static function form(Schema $schema): Schema
    {
        return BranchPlantForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BranchPlantInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BranchPlantsTable::configure($table);
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
            'index' => ListBranchPlants::route('/'),
            'create' => CreateBranchPlant::route('/create'),
            'view' => ViewBranchPlant::route('/{record}'),
            'edit' => EditBranchPlant::route('/{record}/edit'),
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
