<?php

namespace App\Filament\Clusters\Reports\Resources\BrandManagers\Pages;

use App\Filament\Clusters\Reports\Resources\BrandManagers\BrandManagerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBrandManagers extends ListRecords
{
    protected static string $resource = BrandManagerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
