<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    // public function authorize(): bool
    // {
    //     return true;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'psr_uid' => 'required',
            'psr_code' => 'required',
            'order_slip_number' => 'required',
            'order_types' => 'required',
            'personnel' => 'required',
            'division' => 'required',
            'customer' => 'required',
            'branch_plant' => 'required',
            'delivery_mode' => 'required',
            'delivery_date' => 'required',
            'remarks' => '',
            'sku_code' => 'required|array|min:1',
            'sku_code.*' => 'required|string|max:255',
            'uom' => 'required|array',
            'uom.*' => 'required|string|max:50',
            'unit_price' => 'required|array',
            'unit_price.*' => 'required|numeric|min:0',
            'quantity' => 'required|array',
            'quantity.*' => 'required|integer|min:1',
        ];
    }

    public function messages()
    {
        return [
            'sku_code.required' => 'SKU code is required.',
            'sku_code.*.required' => 'SKU code is required for all items.',
            'quantity.*.min' => 'Quantity must be at least 1.',
        ];
    }
}
