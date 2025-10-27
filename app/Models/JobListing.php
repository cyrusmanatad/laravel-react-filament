<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobListing extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'description',
        'responsibilities',
        'location',
        'salary',
        'company_id',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
