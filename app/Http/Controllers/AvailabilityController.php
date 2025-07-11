<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Availability\StoreAvailabilityRequest;
use App\Repositories\Interfaces\AvailabilityRepositoryInterface;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * @OA\Tag(name="Availability")
 */
class AvailabilityController extends Controller
{
    public function __construct(private AvailabilityRepositoryInterface $availabilityRepository) {}

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
    public function store(StoreAvailabilityRequest $request)
    {
        try {
            $data = $request->validated();

            DB::beginTransaction();

            $hasConflict = $this->availabilityRepository->hasOverlap(
                $data['property_id'],
                $data['start_date'],
                $data['end_date']
            );

            if ($hasConflict) {
                DB::rollBack();
                return ApiResponse::error(
                    'This property already has availability set during the selected date range. Please choose non-overlapping dates.',
                    422
                );
            }

            $availability = $this->availabilityRepository->create($data);

            DB::commit();

            return ApiResponse::success($availability, 'Availability added successfully.', 201);

        } catch (Throwable $e) {
            DB::rollBack();
            return ApiResponse::error('An unexpected error occurred.', 500, [
                'exception' => $e->getMessage()
            ]);
        }
    }
}
