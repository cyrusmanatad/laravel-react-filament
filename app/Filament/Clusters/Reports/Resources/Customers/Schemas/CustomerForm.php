<?php

namespace App\Filament\Clusters\Reports\Resources\Customers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('cust_code')->required(),
                TextInput::make('name')->required(),
                TextInput::make('ship_to_address')->required(),
                TextInput::make('bill_to_address'),
                TextInput::make('ship_to_site_name')->required(),
            ]);
    }
}
