<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Services\OrderService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function __construct(protected OrderService $orderService){}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('create-order');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        try {
            $this->orderService->createOrder($request->validated());

            return redirect()
                ->route('orders.create')
                ->with('success', 'Order created successfully!');

        } catch (Exception $e) {
            Log::error('Order creation failed', [
                'error' => $e->getMessage(),
                'data' => $request->validated()
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('failed', 'Failed to create order: ' . $e->getMessage());
        }
    }
}