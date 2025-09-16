<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UtsSku extends Model
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'branch_code',
        'sku_code',
        'div_code',
        'cust_site',
        'sku_desc',
        'sku_uom',
        'sku_price',
        'matrix_price'
    ];
}
