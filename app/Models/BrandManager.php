<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BrandManager extends Model
{
    use SoftDeletes;

    protected array $guarded = [];
}
