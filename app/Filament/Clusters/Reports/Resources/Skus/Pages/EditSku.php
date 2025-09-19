<?php

namespace App\Filament\Clusters\Reports\Resources\Skus\Pages;

use App\Filament\Clusters\Reports\Resources\Skus\SkuResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditSku extends EditRecord
{
    protected static string $resource = SkuResource::class;

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
