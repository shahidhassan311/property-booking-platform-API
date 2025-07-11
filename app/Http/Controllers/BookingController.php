<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Jobs\CreateBookingJob;
use App\Repositories\Interfaces\BookingRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Booking\StoreBookingRequest;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * @OA\Tag(name="Bookings")
 */
class BookingController extends Controller
{
    public function __construct(private BookingRepositoryInterface $bookingRepository) {}

    /**
     * @OA\Get(
     *     path="/api/bookings",
     *     summary="List all bookings (admin only)",
     *     tags={"Bookings"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Success")
     * )
     */
    public function index()
    {
        return $this->bookingRepository->all();
    }

    /**
     * @OA\Post(
     *     path="/api/bookings",
     *     summary="Create a new booking",
     *     tags={"Bookings"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"property_id","start_date","end_date"},
     *             @OA\Property(property="property_id", type="integer", example=1),
     *             @OA\Property(property="start_date", type="string", format="date", example="2025-08-01"),
     *             @OA\Property(property="end_date", type="string", format="date", example="2025-08-05")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Booking created"),
     *     @OA\Response(response=422, description="Property not available or invalid input")
     * )
     */
    public function store(StoreBookingRequest $request)
    {
        try {
            $data = $request->validated();
            $userId = Auth::id();

            $isAvailable = $this->bookingRepository->isAvailable(
                $data['property_id'],
                $data['start_date'],
                $data['end_date']
            );

            if (!$isAvailable) {
                return ApiResponse::error('The property is not available for the selected dates.', 422);
            }

            CreateBookingJob::dispatch($data, $userId);

            return ApiResponse::success([], 'Your booking request is being processed.', 202);

        } catch (Throwable $e) {
            return ApiResponse::error('An unexpected error occurred.', 500, [
                'exception' => $e->getMessage()
            ]);
        }
    }
}
