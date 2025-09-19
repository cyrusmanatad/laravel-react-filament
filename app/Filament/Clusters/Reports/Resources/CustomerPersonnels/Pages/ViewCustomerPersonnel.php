<?php

namespace App\Filament\Clusters\Reports\Resources\CustomerPersonnels\Pages;

use App\Filament\Clusters\Reports\Resources\CustomerPersonnels\CustomerPersonnelResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCustomerPersonnel extends ViewRecord
{
    protected static string $resource = CustomerPersonnelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
