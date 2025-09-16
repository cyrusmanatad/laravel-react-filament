<?php

namespace App\Filament\Clusters\Reports\Resources\Personnels\Pages;

use App\Filament\Clusters\Reports\Resources\Personnels\PersonnelResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPersonnels extends ListRecords
{
    protected static string $resource = PersonnelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
