<?php

namespace App\Filament\Clusters\Reports\Resources\BranchPlants\Pages;

use App\Filament\Clusters\Reports\Resources\BranchPlants\BranchPlantResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewBranchPlant extends ViewRecord
{
    protected static string $resource = BranchPlantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
