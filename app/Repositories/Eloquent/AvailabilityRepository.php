<?php

namespace App\Repositories\Eloquent;

use App\Models\Availability;
use App\Repositories\Interfaces\AvailabilityRepositoryInterface;

class AvailabilityRepository implements AvailabilityRepositoryInterface {
    public function create(array $data) {
        return Availability::create($data);
    }

    public function forProperty($propertyId) {
        return Availability::where('property_id', $propertyId)->get();
    }
}
