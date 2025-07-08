<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

/**
 * @OA\Info(
 *     title="Property Booking API",
 *     version="1.0.0",
 *     description="API documentation for the Property Booking platform"
 * )
 *
 * @OA\SecurityScheme(
 *     type="http",
 *     description="Use bearer token to access these APIs",
 *     name="Authorization",
 *     in="header",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="sanctum"
 * )
 */
abstract class Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
