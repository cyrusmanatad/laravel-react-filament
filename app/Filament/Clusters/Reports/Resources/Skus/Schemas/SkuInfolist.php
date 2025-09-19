<?php

namespace App\Filament\Clusters\Reports\Resources\Skus\Schemas;

use App\Models\Sku;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SkuInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('sku_code'),
                TextEntry::make('sku_desc'),
                TextEntry::make('tagging'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Sku $record): bool => $record->trashed()),
            ]);
    }
}
