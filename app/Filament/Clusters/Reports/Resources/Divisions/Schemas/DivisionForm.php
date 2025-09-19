<?php

namespace App\Filament\Clusters\Reports\Resources\Divisions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DivisionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('div_code')
                    ->required(),
                TextInput::make('div_desc')
                    ->required(),
            ]);
    }
}
