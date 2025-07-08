<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\BookingRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(name="Bookings")
 */
class BookingController extends Controller
{
    public function __construct(private BookingRepositoryInterface $bookingRepo) {}

    /**
     * @OA\Get(
     *     path="/api/bookings",
     *     summary="List all bookings (admin only)",
     *     tags={"Bookings"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Success")
     * )
     */
    public function index() {
        return $this->bookingRepo->all();
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
    public function store(Request $request) {
        $data = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        if (! $this->bookingRepo->isAvailable($data['property_id'], $data['start_date'], $data['end_date'])) {
            return response()->json(['error' => 'Property not available for these dates'], 422);
        }

        return $this->bookingRepo->create([
            ...$data,
            'user_id' => Auth::id(),
            'status' => 'pending'
        ]);
    }
}
