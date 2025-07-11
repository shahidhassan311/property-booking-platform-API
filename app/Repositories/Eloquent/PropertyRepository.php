<?php

namespace App\Repositories\Eloquent;

use App\Models\Property;
use App\Repositories\Interfaces\PropertyRepositoryInterface;

class PropertyRepository implements PropertyRepositoryInterface
{
    public function all()
    {
        return Property::all();
    }

    public function filterProperties(array $filters)
    {
        $query = Property::query();

        if (!empty($filters['location'])) {
            $query->where('location', 'like', '%' . $filters['location'] . '%');
        }

        if (!empty($filters['min_price'])) {
            $query->where('price_per_night', '>=', $filters['min_price']);
        }

        if (!empty($filters['max_price'])) {
            $query->where('price_per_night', '<=', $filters['max_price']);
        }

        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->whereHas('availabilities', function ($q) use ($filters) {
                $q->where('start_date', '<=', $filters['start_date'])
                    ->where('end_date', '>=', $filters['end_date']);
            });
        }

        return $query->with('availabilities')->latest()->paginate(10);
    }

    public function find($id) {
        return Property::with(['availabilities', 'booking'])->findOrFail($id);
    }

    public function create(array $data) {
        return Property::create($data);
    }

    public function update($id, array $data) {
        $property = Property::findOrFail($id);
        $property->update($data);
        return $property;
    }

    public function delete($id) {
        $property = Property::findOrFail($id);
        $property->delete();
    }
}
