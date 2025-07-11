<?php

namespace App\Repositories\Eloquent;

use App\Models\Availability;
use App\Models\Booking;
use App\Repositories\Interfaces\BookingRepositoryInterface;

class BookingRepository implements BookingRepositoryInterface
{
    public function all()
    {
        return Booking::with(['user', 'property'])->get();
    }

    public function create(array $data)
    {
        return Booking::create($data);
    }

    public function isAvailable($propertyId, $startDate, $endDate): bool
    {
        $withinAvailability = Availability::where('property_id', $propertyId)
            ->where('start_date', '<=', $startDate)
            ->where('end_date', '>=', $endDate)
            ->exists();

        if (! $withinAvailability) {
            return false;
        }

        $hasConflict = Booking::where('property_id', $propertyId)
            ->where('status', 'confirmed')
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                        $q->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                    });
            })
            ->exists();

        return !$hasConflict;
    }
}
