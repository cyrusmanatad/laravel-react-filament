<?php

namespace App\Filament\Clusters\Reports\Resources\Divisions\Schemas;

use App\Models\Division;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DivisionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('div_code'),
                TextEntry::make('div_desc'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Division $record): bool => $record->trashed()),
            ]);
    }
}
