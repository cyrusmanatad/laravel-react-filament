<?php

namespace App\Filament\Clusters\Reports\Resources\UtsSkus\Pages;

use App\Filament\Clusters\Reports\Resources\UtsSkus\UtsSkuResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditUtsSku extends EditRecord
{
    protected static string $resource = UtsSkuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
