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
                'order_type' => $validatedData['order_types'],
                'psr_code' => $validatedData['psr_code'],
                'order_slip_number' =>$validatedData[ 'order_slip_number'],
                'cust_code' => $validatedData['customer'],
                'div_code' => $validatedData['division'],
                'branch_code' => $validatedData['branch_plant'],
                'delivery_mode' => $validatedData['delivery_mode'],
                'remarks' => $validatedData['remarks'],
                'delivery_date' => $validatedData['delivery_date']
            ]);

            $items = $this->prepareItems($validatedData);

            // Process each item
            foreach ($items as $itemData) {
                $this->createOrderItem($order, $itemData);
            }

            return $order->fresh(); // Return fresh instance with updated data
        });
    }

    /**
     * Prepare items array from form data
     */
    private function prepareItems(array $data): array
    {
        $items = [];
        $count = count($data['sku_code']);

        for ($i = 0; $i < $count; $i++) {
            
            $items[] = [
                'sku_code' => $data['sku_code'][$i],
                'price' => $data['unit_price'][$i],
                'uom' => $data['uom'][$i],
                'sku_type' => null,
                'ref_item' => null,
                'qty' => $data['quantity'][$i],
            ];
        }

        return $items;
    }

    /**
     * Create an order item
     */
    private function createOrderItem(Order $order, array $itemData): OrderItem
    {
        return OrderItem::create(['order_slip_number' => $order->order_slip_number, ...$itemData]);
    }
}