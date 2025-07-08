<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when not authenticated.
     */
    protected function redirectTo($request): ?string
    {
        // For APIs, return null to send 401 instead of redirect
        return $request->expectsJson() ? null : route('login');
    }
}
