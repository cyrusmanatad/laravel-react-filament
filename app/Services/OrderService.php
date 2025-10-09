<?php
// app/Services/OrderService.php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Exception;

class OrderService
{
    /**
     * Create a new order with items
     */
    public function createOrder(array $validatedData): Order
    {
        return DB::transaction(function () use ($validatedData) {
            // Create the main order
            $order = Order::create([
                'psr_uid' => $validatedData['psr_uid'],
                'order_type' => $validatedData['order_type'],
                'psr_code' => $validatedData['psr_code'],
                'order_slip_number' => $validatedData[ 'order_slip_number'],
                'cust_code' => $validatedData['cust_code'],
                'div_code' => $validatedData['div_code'],
                'branch_code' => $validatedData['branch_code'],
                'delivery_mode' => $validatedData['delivery_mode'],
                'remarks' => $validatedData['remarks'],
                'delivery_date' => $validatedData['delivery_date'],
                'status' => 'approved',
                'attempt' => 0,
            ]);

            $items = array_map(fn($item) => [
                'sku_code' => $item['sku_code'],
                'price' => $item['unit_price'],
                'uom' => $item['uom'],
                'sku_type' => null,
                'ref_item' => null,
                'qty' => $item['quantity'],
            ], $validatedData['items']);

            // Process each item
            foreach ($items as $itemData) {
                OrderItem::create(['order_slip_number' => $validatedData[ 'order_slip_number'], ...$itemData]);
            }

            return $order->fresh(); // Return fresh instance with updated data
        });
    }
}