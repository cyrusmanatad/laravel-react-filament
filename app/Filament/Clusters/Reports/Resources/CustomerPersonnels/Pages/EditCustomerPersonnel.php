<?php

namespace App\Filament\Clusters\Reports\Resources\CustomerPersonnels\Pages;

use App\Filament\Clusters\Reports\Resources\CustomerPersonnels\CustomerPersonnelResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditCustomerPersonnel extends EditRecord
{
    protected static string $resource = CustomerPersonnelResource::class;

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
