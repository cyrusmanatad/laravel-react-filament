<?php

namespace App\Filament\Clusters\Reports\Resources\CustomerPersonnels\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CustomerPersonnelForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('cust_code')
                    ->required(),
                TextInput::make('div_code')
                    ->required(),
                TextInput::make('psr_code')
                    ->required(),
                TextInput::make('emp_id'),
                TextInput::make('bp_code')
                    ->required(),
                TextInput::make('bm_code')
                    ->required(),
            ]);
    }
}
