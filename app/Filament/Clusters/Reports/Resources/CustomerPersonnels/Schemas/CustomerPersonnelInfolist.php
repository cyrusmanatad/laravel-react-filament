<?php

namespace App\Filament\Clusters\Reports\Resources\CustomerPersonnels\Schemas;

use App\Models\CustomerPersonnel;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CustomerPersonnelInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('cust_code'),
                TextEntry::make('div_code'),
                TextEntry::make('psr_code'),
                TextEntry::make('emp_id')
                    ->placeholder('-'),
                TextEntry::make('bp_code'),
                TextEntry::make('bm_code'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (CustomerPersonnel $record): bool => $record->trashed()),
            ]);
    }
}
