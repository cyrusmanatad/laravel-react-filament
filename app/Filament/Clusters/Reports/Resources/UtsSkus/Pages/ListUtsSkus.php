<?php

namespace App\Filament\Clusters\Reports\Resources\UtsSkus\Pages;

use App\Filament\Clusters\Reports\Resources\UtsSkus\UtsSkuResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListUtsSkus extends ListRecords
{
    protected static string $resource = UtsSkuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
