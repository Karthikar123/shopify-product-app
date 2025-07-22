<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    // The table associated with the model
    protected $table = 'locations';

    // Fillable columns for mass assignment
    protected $fillable = [
        'location_id',
        'name',
        'address',
    ];
}
