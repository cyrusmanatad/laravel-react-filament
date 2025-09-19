<?php

namespace App\Filament\Clusters\Reports\Resources\BrandManagers\Pages;

use App\Filament\Clusters\Reports\Resources\BrandManagers\BrandManagerResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditBrandManager extends EditRecord
{
    protected static string $resource = BrandManagerResource::class;

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
