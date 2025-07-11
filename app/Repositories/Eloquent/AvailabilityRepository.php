<?php

namespace App\Repositories\Eloquent;

use App\Models\Availability;
use App\Repositories\Interfaces\AvailabilityRepositoryInterface;

class AvailabilityRepository implements AvailabilityRepositoryInterface
{
    public function create(array $data)
    {
        return Availability::create($data);
    }

    public function hasOverlap($propertyId, $startDate, $endDate): bool
    {
        return Availability::where('property_id', $propertyId)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                        $q->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                    });
            })
            ->exists();
    }
}
