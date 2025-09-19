<?php

namespace App\Filament\Clusters\Reports\Resources\BrandManagers\Schemas;

use App\Models\BrandManager;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class BrandManagerInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('site_user_id'),
                TextEntry::make('cust_code'),
                TextEntry::make('bm_email')
                    ->placeholder('-'),
                TextEntry::make('bm_code'),
                TextEntry::make('bm_name')
                    ->placeholder('-'),
                TextEntry::make('bp_code'),
                TextEntry::make('bp_name')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (BrandManager $record): bool => $record->trashed()),
            ]);
    }
}
