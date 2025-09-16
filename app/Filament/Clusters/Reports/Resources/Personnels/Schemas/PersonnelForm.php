<?php

namespace App\Filament\Clusters\Reports\Resources\Personnels\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PersonnelForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('psr_code')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
            ]);
    }
}
