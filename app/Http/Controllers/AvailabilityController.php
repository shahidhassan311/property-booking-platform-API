<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\AvailabilityRepositoryInterface;
use Illuminate\Http\Request;

/**
 * @OA\Tag(name="Availability")
 */
class AvailabilityController extends Controller
{
    public function __construct(private AvailabilityRepositoryInterface $availabilityRepo) {}

    /**
     * @OA\Post(
     *     path="/api/availability",
     *     summary="Add availability range for a property",
     *     tags={"Availability"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"property_id","start_date","end_date"},
     *             @OA\Property(property="property_id", type="integer", example=1),
     *             @OA\Property(property="start_date", type="string", format="date", example="2025-07-15"),
     *             @OA\Property(property="end_date", type="string", format="date", example="2025-07-20")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Availability added"),
     *     @OA\Response(response=422, description="Invalid input or date range")
     * )
     */
    public function store(Request $request) {
        $data = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        return $this->availabilityRepo->create($data);
    }
}
