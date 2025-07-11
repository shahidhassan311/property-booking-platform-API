<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $fillable = [
        'title',
        'description',
        'price_per_night',
        'location',
        'amenities',
        'images',
    ];

    protected $casts = [
        'amenities' => 'array',
        'images' => 'array',
        'price_per_night' => 'decimal:2',
    ];

    public function availabilities()
    {
        return $this->hasMany(Availability::class);
    }

    public function booking()
    {
        return $this->hasMany(Booking::class);
    }
}
