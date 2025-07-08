<?php

namespace App\Repositories\Interfaces;


interface AvailabilityRepositoryInterface {
    public function create(array $data);
    public function forProperty($propertyId);
}
