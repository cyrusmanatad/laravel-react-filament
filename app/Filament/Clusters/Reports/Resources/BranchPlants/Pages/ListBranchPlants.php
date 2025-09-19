<?php

namespace App\Filament\Clusters\Reports\Resources\BranchPlants\Pages;

use App\Filament\Clusters\Reports\Resources\BranchPlants\BranchPlantResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBranchPlants extends ListRecords
{
    protected static string $resource = BranchPlantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
