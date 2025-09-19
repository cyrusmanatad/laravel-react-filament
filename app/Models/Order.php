<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory; //, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'psr_uid',
        'order_type',
        'psr_code',
        'order_slip_number',
        'cust_code',
        'div_code',
        'branch_code',
        'delivery_mode',
        'remarks',
        'delivery_date',
        'status',
        'invoice',
        'attempt',
    ];
}
