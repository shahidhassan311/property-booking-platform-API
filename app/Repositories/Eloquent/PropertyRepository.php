<?php

namespace App\Repositories\Eloquent;

use App\Models\Property;
use App\Repositories\Interfaces\PropertyRepositoryInterface;

class PropertyRepository implements PropertyRepositoryInterface {
    public function all() {
        return Property::all();
    }

    public function find($id) {
        return Property::with('availabilities')->findOrFail($id);
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
        Property::findOrFail($id)->delete();
    }
}
