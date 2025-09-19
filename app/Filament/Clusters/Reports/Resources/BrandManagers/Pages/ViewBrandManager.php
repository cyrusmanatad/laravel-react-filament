<?php

namespace App\Filament\Clusters\Reports\Resources\BrandManagers\Pages;

use App\Filament\Clusters\Reports\Resources\BrandManagers\BrandManagerResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewBrandManager extends ViewRecord
{
    protected static string $resource = BrandManagerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
