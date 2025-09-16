<?php

namespace App\Filament\Clusters\Reports\Resources\Personnels;

use App\Filament\Clusters\Reports\ReportsCluster;
use App\Filament\Clusters\Reports\Resources\Personnels\Pages\CreatePersonnel;
use App\Filament\Clusters\Reports\Resources\Personnels\Pages\EditPersonnel;
use App\Filament\Clusters\Reports\Resources\Personnels\Pages\ListPersonnels;
use App\Filament\Clusters\Reports\Resources\Personnels\Pages\ViewPersonnel;
use App\Filament\Clusters\Reports\Resources\Personnels\Schemas\PersonnelForm;
use App\Filament\Clusters\Reports\Resources\Personnels\Schemas\PersonnelInfolist;
use App\Filament\Clusters\Reports\Resources\Personnels\Tables\PersonnelsTable;
use App\Models\Personnel;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PersonnelResource extends Resource
{
    protected static ?string $model = Personnel::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::UserGroup;

    protected static ?string $cluster = ReportsCluster::class;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return PersonnelForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PersonnelInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PersonnelsTable::configure($table);
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
            'index' => ListPersonnels::route('/'),
            'create' => CreatePersonnel::route('/create'),
            'view' => ViewPersonnel::route('/{record}'),
            'edit' => EditPersonnel::route('/{record}/edit'),
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
