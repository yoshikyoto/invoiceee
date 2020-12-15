<?php

namespace App\Http\Middleware;

use App\User\UserRepository;
use Illuminate\Http\Request;

class Authenticate
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handler(Request $request, \Closure $next)
    {
        $userId = $request->session()->get('userId');
        var_dump($userId);
        $this->userRepository->get($userId);
        $next($request);
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
