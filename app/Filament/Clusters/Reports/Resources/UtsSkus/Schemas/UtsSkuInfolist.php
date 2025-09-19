<?php

namespace App\Filament\Clusters\Reports\Resources\UtsSkus\Schemas;

use App\Models\UtsSku;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class UtsSkuInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('branch_code')
                    ->placeholder('-'),
                TextEntry::make('sku_code'),
                TextEntry::make('div_code'),
                TextEntry::make('cust_site')
                    ->placeholder('-'),
                TextEntry::make('sku_desc'),
                TextEntry::make('sku_uom'),
                TextEntry::make('sku_price')
                    ->placeholder('-'),
                TextEntry::make('matrix_price')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (UtsSku $record): bool => $record->trashed()),
            ]);
    }
}
