<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerPersonnel extends Model
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_code_id',
        'div_code',
        'psr_code',
        'emp_id',
        'bp_code',
        'bm_code'
    ];

    public function customers()
    {
        return $this->hasMany(Customer::class, 'cust_code');
    }
}
