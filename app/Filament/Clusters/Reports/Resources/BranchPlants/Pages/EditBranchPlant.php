<?php

namespace App\Filament\Clusters\Reports\Resources\BranchPlants\Pages;

use App\Filament\Clusters\Reports\Resources\BranchPlants\BranchPlantResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditBranchPlant extends EditRecord
{
    protected static string $resource = BranchPlantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
