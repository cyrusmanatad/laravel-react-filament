<?php

namespace App\Filament\Clusters\Reports\Resources\Personnels\Pages;

use App\Filament\Clusters\Reports\Resources\Personnels\PersonnelResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePersonnel extends CreateRecord
{
    protected static string $resource = PersonnelResource::class;
}
