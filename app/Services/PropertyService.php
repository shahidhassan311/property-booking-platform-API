<?php
namespace App\Services;


use App\Repositories\Interfaces\PropertyRepositoryInterface;

class PropertyService {
    public function __construct(private PropertyRepositoryInterface $propertyRepository) {}

    public function list()
    {
        return $this->propertyRepository->all();
    }

    public function filterProperties(array $filters)
    {
        return $this->propertyRepository->filterProperties($filters);
    }

    public function get($id)
    {
        return $this->propertyRepository->find($id);
    }

    public function create(array $data)
    {
        return $this->propertyRepository->create($data);
    }

    public function update($id, array $data)
    {
        $property = $this->propertyRepository->find($id);

        if (!$property) {
            throw new \Exception("Property not found.");
        }

        $property->update($data);

        return $property;
    }

    public function delete($id)
    {
        return $this->propertyRepository->delete($id);
    }
}
