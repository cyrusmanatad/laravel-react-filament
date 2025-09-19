<?php

namespace App\Filament\Clusters\Reports\Resources\CustomerPersonnels;

use App\Filament\Clusters\Reports\ReportsCluster;
use App\Filament\Clusters\Reports\Resources\CustomerPersonnels\Pages\CreateCustomerPersonnel;
use App\Filament\Clusters\Reports\Resources\CustomerPersonnels\Pages\EditCustomerPersonnel;
use App\Filament\Clusters\Reports\Resources\CustomerPersonnels\Pages\ListCustomerPersonnels;
use App\Filament\Clusters\Reports\Resources\CustomerPersonnels\Pages\ViewCustomerPersonnel;
use App\Filament\Clusters\Reports\Resources\CustomerPersonnels\Schemas\CustomerPersonnelForm;
use App\Filament\Clusters\Reports\Resources\CustomerPersonnels\Schemas\CustomerPersonnelInfolist;
use App\Filament\Clusters\Reports\Resources\CustomerPersonnels\Tables\CustomerPersonnelsTable;
use App\Models\CustomerPersonnel;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerPersonnelResource extends Resource
{
    protected static ?string $model = CustomerPersonnel::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Link;

    protected static ?string $cluster = ReportsCluster::class;

    public static function form(Schema $schema): Schema
    {
        return CustomerPersonnelForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CustomerPersonnelInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CustomerPersonnelsTable::configure($table);
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
            'index' => ListCustomerPersonnels::route('/'),
            'create' => CreateCustomerPersonnel::route('/create'),
            'view' => ViewCustomerPersonnel::route('/{record}'),
            'edit' => EditCustomerPersonnel::route('/{record}/edit'),
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
