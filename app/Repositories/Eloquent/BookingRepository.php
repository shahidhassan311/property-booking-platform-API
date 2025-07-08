<?php

namespace App\Repositories\Eloquent;

use App\Models\Availability;
use App\Models\Booking;
use App\Repositories\Interfaces\BookingRepositoryInterface;

class BookingRepository implements BookingRepositoryInterface {
    public function all() {
        return Booking::with(['user', 'property'])->get();
    }

    public function create(array $data) {
        return Booking::create($data);
    }

    public function isAvailable($propertyId, $startDate, $endDate) {
        return Availability::where('property_id', $propertyId)
            ->where('start_date', '<=', $startDate)
            ->where('end_date', '>=', $endDate)
            ->exists();
    }
}
