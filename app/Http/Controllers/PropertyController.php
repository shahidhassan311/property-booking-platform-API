<?php

namespace App\Http\Controllers;

use App\Http\Requests\Property\PropertyRequest;
use App\Services\PropertyService;
use Illuminate\Http\Request;

/**
 * @OA\Tag(name="Properties")
 */
class PropertyController extends Controller
{
    public function __construct(private PropertyService $service) {}

    /**
     * @OA\Get(
     *     path="/api/properties",
     *     summary="List all properties",
     *     tags={"Properties"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="List of properties")
     * )
     */
    public function index(Request $request)
    {
        return $this->service->list();
    }

    /**
     * @OA\Get(
     *     path="/api/properties/{id}",
     *     summary="Get property details",
     *     tags={"Properties"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Property found")
     * )
     */
    public function show($id)
    {
        return $this->service->get($id);
    }

    /**
     * @OA\Post(
     *     path="/api/properties",
     *     summary="Create new property",
     *     tags={"Properties"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title","description","price_per_night","location"},
     *             @OA\Property(property="title", type="string", example="Beachfront Villa"),
     *             @OA\Property(property="description", type="string", example="Spacious villa with ocean view"),
     *             @OA\Property(property="price_per_night", type="number", example=250.00),
     *             @OA\Property(property="location", type="string", example="Gold Coast"),
     *             @OA\Property(
     *                 property="amenities",
     *                 type="array",
     *                 @OA\Items(type="string", example="WiFi"),
     *                 example={"WiFi", "AC", "Pool", "Parking", "Kitchen", "Netflix", "Gym"}
     *             ),
     *             @OA\Property(
     *                 property="images",
     *                 type="array",
     *                 @OA\Items(type="string", example="https://example.com/image1.jpg"),
     *                 example={
     *                     "https://example.com/image1.jpg",
     *                     "https://example.com/image2.jpg",
     *                     "https://example.com/image3.jpg"
     *                 }
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="Property created")
     * )
     */

    public function store(PropertyRequest $request)
    {
        return $this->service->create($request->validated());
    }

    /**
     * @OA\Put(
     *     path="/api/properties/{id}",
     *     summary="Update property",
     *     tags={"Properties"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title","description","price_per_night","location"},
     *             @OA\Property(property="title", type="string", example="Updated Villa Name"),
     *             @OA\Property(property="description", type="string", example="Updated villa description"),
     *             @OA\Property(property="price_per_night", type="number", example=300.00),
     *             @OA\Property(property="location", type="string", example="Updated Location"),
     *             @OA\Property(property="amenities", type="array", @OA\Items(type="string", example="Pool")),
     *             @OA\Property(property="images", type="array", @OA\Items(type="string", example="https://example.com/newimage.jpg"))
     *         )
     *     ),
     *     @OA\Response(response=200, description="Property updated")
     * )
     */
    public function update(PropertyRequest $request, $id)
    {
        return $this->service->update($id, $request->validated());
    }

    /**
     * @OA\Delete(
     *     path="/api/properties/{id}",
     *     summary="Delete property",
     *     tags={"Properties"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Property deleted")
     * )
     */
    public function destroy($id)
    {
        $this->service->delete($id);
        return response()->json(['message' => 'Deleted']);
    }
}
