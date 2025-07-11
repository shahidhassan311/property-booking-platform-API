<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\DB;
use Throwable;


/**
 * @OA\Tag(name="Auth")
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Register a new user (admin or guest)",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password","role"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string", format="password"),
     *             @OA\Property(property="role", type="string", enum={"admin", "guest"})
     *         )
     *     ),
     *     @OA\Response(response=201, description="User registered")
     * )
     */
    public function register(RegisterRequest $request)
    {
        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            DB::commit();

            return ApiResponse::success($user, 'User registered successfully.', 201);

        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Registration error', ['exception' => $e]);
            return ApiResponse::error('An unexpected error occurred during registration.', 500, [
                'exception' => $e->getMessage()
            ]);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Login and create token",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string", format="password")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Login successful, returns token"),
     *     @OA\Response(response=422, description="Invalid credentials")
     * )
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $request->authenticate();

            $user = $request->user();

            $token = $user->createToken('auth_token')->plainTextToken;

            return ApiResponse::success([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'role' => $user->role,
            ], 'Login successful.', 200);

        } catch (ValidationException $e) {
            return ApiResponse::error('Invalid login credentials.', 422, $e->errors());

        } catch (Throwable $e) {
            Log::error('Login error', ['exception' => $e]);
            return ApiResponse::error('An unexpected error occurred during login.', 500, [
                'exception' => $e->getMessage()
            ]);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Logout user (revoke token)",
     *     tags={"Auth"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Logout successful")
     * )
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out']);
    }
}
