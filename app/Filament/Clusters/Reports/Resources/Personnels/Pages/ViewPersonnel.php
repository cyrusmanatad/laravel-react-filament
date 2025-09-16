<?php

namespace App\Filament\Clusters\Reports\Resources\Personnels\Pages;

use App\Filament\Clusters\Reports\Resources\Personnels\PersonnelResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPersonnel extends ViewRecord
{
    protected static string $resource = PersonnelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
