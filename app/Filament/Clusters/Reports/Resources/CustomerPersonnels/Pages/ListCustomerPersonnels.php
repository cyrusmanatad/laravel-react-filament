<?php

namespace App\Filament\Clusters\Reports\Resources\CustomerPersonnels\Pages;

use App\Filament\Clusters\Reports\Resources\CustomerPersonnels\CustomerPersonnelResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCustomerPersonnels extends ListRecords
{
    protected static string $resource = CustomerPersonnelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
