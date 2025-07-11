<?php

namespace App\Http\Controllers;

use App\Http\Requests\Property\PropertyRequest;
use App\Services\PropertyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\ApiResponse;
use Throwable;
/**
 * @OA\Tag(name="Properties")
 */
class PropertyController extends Controller
{
    public function __construct(private PropertyService $propertyService) {}

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
        return $this->propertyService->list();
    }

    /**
     * @OA\Get(
     *     path="/api/properties/filter",
     *     summary="Filter properties by location, price range, and availability",
     *     tags={"Properties"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="location",
     *         in="query",
     *         description="Filter by location",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="min_price",
     *         in="query",
     *         description="Minimum price per night",
     *         required=false,
     *         @OA\Schema(type="number")
     *     ),
     *     @OA\Parameter(
     *         name="max_price",
     *         in="query",
     *         description="Maximum price per night",
     *         required=false,
     *         @OA\Schema(type="number")
     *     ),
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         description="Start date of availability (YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         description="End date of availability (YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of filtered properties",
     *     )
     * )
     */
    public function filter(Request $request)
    {
        try {
            $filters = $request->only([
                'location',
                'min_price',
                'max_price',
                'start_date',
                'end_date'
            ]);

            $properties = $this->propertyService->filterProperties($filters);

            return ApiResponse::success($properties, 'Properties fetched successfully.');

        } catch (\Throwable $e) {
            return ApiResponse::error('Failed to fetch properties.', 500, [
                'exception' => $e->getMessage()
            ]);
        }
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
        return $this->propertyService->get($id);
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
        try {
            $data = $request->validated();

            DB::beginTransaction();

            $property = $this->propertyService->create($data);

            DB::commit();

            return ApiResponse::success($property, 'Property created successfully.', 201);

        } catch (Throwable $e) {
            DB::rollBack();

            return ApiResponse::error('An error occurred while creating the property.', 500, [
                'exception' => $e->getMessage()
            ]);
        }
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
        try {
            $data = $request->validated();

            DB::beginTransaction();

            $property = $this->propertyService->update($id, $data);

            DB::commit();

            return ApiResponse::success($property, 'Property updated successfully.', 200);

        } catch (Throwable $e) {
            DB::rollBack();

            return ApiResponse::error('An error occurred while updating the property.', 500, [
                'exception' => $e->getMessage()
            ]);
        }
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
        try {
            DB::beginTransaction();

            $this->propertyService->delete($id);

            DB::commit();

            return ApiResponse::success([], 'Property deleted successfully.', 200);

        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return ApiResponse::error('Property not found.', 404);

        } catch (Throwable $e) {
            DB::rollBack();
            return ApiResponse::error('An error occurred while deleting the property.', 500, [
                'exception' => $e->getMessage()
            ]);
        }
    }
}
