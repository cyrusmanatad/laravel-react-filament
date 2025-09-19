<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('psr_uid'),
                TextEntry::make('order_type'),
                TextEntry::make('psr_code'),
                TextEntry::make('order_slip_number'),
                TextEntry::make('cust_code'),
                TextEntry::make('div_code'),
                TextEntry::make('branch_code'),
                TextEntry::make('delivery_mode'),
                TextEntry::make('remarks')
                    ->placeholder('-'),
                TextEntry::make('delivery_date')
                    ->date(),
                TextEntry::make('status'),
                TextEntry::make('invoice')
                    ->placeholder('-'),
                TextEntry::make('attempt')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
