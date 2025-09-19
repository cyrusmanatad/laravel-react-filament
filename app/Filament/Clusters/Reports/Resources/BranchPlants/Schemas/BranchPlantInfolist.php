<?php

namespace App\Filament\Clusters\Reports\Resources\BranchPlants\Schemas;

use App\Models\BranchPlant;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class BranchPlantInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('branch_code'),
                TextEntry::make('branch_desc'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (BranchPlant $record): bool => $record->trashed()),
            ]);
    }
}
