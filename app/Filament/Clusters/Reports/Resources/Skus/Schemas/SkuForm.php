<?php

namespace App\Filament\Clusters\Reports\Resources\Skus\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SkuForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('sku_code')
                    ->required(),
                TextInput::make('sku_desc')
                    ->required(),
                TextInput::make('tagging')
                    ->required(),
            ]);
    }
}
