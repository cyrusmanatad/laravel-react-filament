<?php

namespace App\Filament\Clusters\Reports\Resources\Skus\Pages;

use App\Filament\Clusters\Reports\Resources\Skus\SkuResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSkus extends ListRecords
{
    protected static string $resource = SkuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
