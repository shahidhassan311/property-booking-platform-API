<?php
namespace App\Services;


use App\Repositories\Interfaces\PropertyRepositoryInterface;

class PropertyService {
    public function __construct(private PropertyRepositoryInterface $propertyRepo) {}

    public function list() {
        return $this->propertyRepo->all();
    }

    public function get($id) {
        return $this->propertyRepo->find($id);
    }

    public function create(array $data) {
        return $this->propertyRepo->create($data);
    }

    public function update($id, array $data) {
        return $this->propertyRepo->update($id, $data);
    }

    public function delete($id) {
        return $this->propertyRepo->delete($id);
    }
}
