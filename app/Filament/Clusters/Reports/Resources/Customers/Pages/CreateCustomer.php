<?php

namespace App\Filament\Clusters\Reports\Resources\Customers\Pages;

use App\Filament\Clusters\Reports\Resources\Customers\CustomerResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomer extends CreateRecord
{
    protected static string $resource = CustomerResource::class;
}
