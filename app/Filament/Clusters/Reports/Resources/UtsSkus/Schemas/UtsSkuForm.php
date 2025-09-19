<?php

namespace App\Filament\Clusters\Reports\Resources\UtsSkus\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UtsSkuForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('branch_code'),
                TextInput::make('sku_code')
                    ->required(),
                TextInput::make('div_code')
                    ->required(),
                TextInput::make('cust_site'),
                TextInput::make('sku_desc')
                    ->required(),
                TextInput::make('sku_uom')
                    ->required(),
                TextInput::make('sku_price'),
                TextInput::make('matrix_price'),
            ]);
    }
}
