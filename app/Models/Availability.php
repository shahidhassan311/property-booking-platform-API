<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    protected $fillable = [
        'property_id',
        'start_date',
        'end_date',
    ];
}
