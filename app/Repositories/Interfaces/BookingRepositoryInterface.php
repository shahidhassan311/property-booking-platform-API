<?php

namespace App\Repositories\Interfaces;

interface BookingRepositoryInterface {

    public function all();

    public function create(array $data);

    public function isAvailable($propertyId, $startDate, $endDate);
}
