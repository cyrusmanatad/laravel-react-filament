<?php

namespace App\Filament\Clusters\Reports\Resources\CustomerPersonnels\Pages;

use App\Filament\Clusters\Reports\Resources\CustomerPersonnels\CustomerPersonnelResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomerPersonnel extends CreateRecord
{
    protected static string $resource = CustomerPersonnelResource::class;
}
