<?php

namespace App\Filament\Clusters\Reports\Resources\BrandManagers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BrandManagerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('site_user_id')
                    ->required(),
                TextInput::make('cust_code')
                    ->required(),
                TextInput::make('bm_email')
                    ->email(),
                TextInput::make('bm_code')
                    ->required(),
                TextInput::make('bm_name'),
                TextInput::make('bp_code')
                    ->required(),
                TextInput::make('bp_name'),
            ]);
    }
}
