<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('psr_uid')
                    ->required(),
                TextInput::make('order_type')
                    ->required(),
                TextInput::make('psr_code')
                    ->required(),
                TextInput::make('order_slip_number')
                    ->required(),
                TextInput::make('cust_code')
                    ->required(),
                TextInput::make('div_code')
                    ->required(),
                TextInput::make('branch_code')
                    ->required(),
                TextInput::make('delivery_mode')
                    ->required(),
                TextInput::make('remarks'),
                DatePicker::make('delivery_date')
                    ->required(),
                TextInput::make('status')
                    ->required(),
                TextInput::make('invoice'),
                TextInput::make('attempt')
                    ->required()
                    ->numeric(),
            ]);
    }
}
