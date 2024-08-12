<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        //return $request->expectsJson() ? null : route('login');
        if ($request->expectsJson()) {
            return response()->json([
                'error' => 'Authentication is false, please login.'
            ], 401);
        }else{
            return response()->json([
                'error' => 'Authentication is false, please login.'
            ], 401);
        }
    }
}
