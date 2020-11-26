<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;

class Authenticate
{

    public function handler(Request $request, \Closure $next)
    {
        $userId = $request->session()->get('userId');
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }
}
