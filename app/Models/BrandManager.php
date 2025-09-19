<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BrandManager extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'site_user_id',
        'cust_code',
        'bm_email',
        'bm_code',
        'bm_name',
        'bp_code',
        'bp_name'
    ];
}
