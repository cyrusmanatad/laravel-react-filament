<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'contactEmail',
        'contactPhone',
    ];

    public function jobListings()
    {
        return $this->hasMany(JobListing::class);
    }
}
