<?php

namespace App\Filament\Clusters\Reports\Resources\BranchPlants\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BranchPlantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('branch_code')
                    ->required(),
                TextInput::make('branch_desc')
                    ->required(),
            ]);
    }
}
