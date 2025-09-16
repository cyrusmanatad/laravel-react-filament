<?php

namespace App\Filament\Clusters\Reports\Resources\Personnels\Schemas;

use App\Models\Personnel;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PersonnelInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('psr_code'),
                TextEntry::make('name'),
                TextEntry::make('email')
                    ->label('Email address')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Personnel $record): bool => $record->trashed()),
            ]);
    }
}
