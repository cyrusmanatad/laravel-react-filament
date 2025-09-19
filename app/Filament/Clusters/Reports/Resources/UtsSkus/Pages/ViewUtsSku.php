<?php

namespace App\Filament\Clusters\Reports\Resources\UtsSkus\Pages;

use App\Filament\Clusters\Reports\Resources\UtsSkus\UtsSkuResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewUtsSku extends ViewRecord
{
    protected static string $resource = UtsSkuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
