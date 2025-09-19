<?php

namespace App\Filament\Clusters\Reports\Resources\Divisions\Pages;

use App\Filament\Clusters\Reports\Resources\Divisions\DivisionResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDivision extends ViewRecord
{
    protected static string $resource = DivisionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
