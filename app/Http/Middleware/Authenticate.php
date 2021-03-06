<?php

namespace App\Http\Middleware;

use App\User\User;
use App\User\UserRepository;
use Illuminate\Http\Request;
use Psr\Log\LoggerInterface;

class Authenticate
{
    private UserRepository $userRepository;
    private LoggerInterface $logger;

    public function __construct(
        UserRepository $userRepository,
        LoggerInterface $logger
    ) {
        $this->userRepository = $userRepository;
        $this->logger = $logger;
    }

    public function handle(Request $request, \Closure $next)
    {
        $userId = $request->session()->get('userId');
        $user = $this->getUserFromSession($userId);
        $this->log($userId, $user);
        if ($user === null) {
            return $this->redirectToLoginPage();
        }
        return $next($request);
    }

    private function getUserFromSession(?int $userId): ?User
    {
        if ($userId === null) {
            return null;
        }

        return $this->userRepository->get($userId);
    }

    private function log(?int $userId, ?User $user)
    {
        $this->logger->info('認証ミドルウェア', [
            'userId' => $userId,
            'user' => $user,
        ]);
    }

    private function redirectToLoginPage()
    {
        return redirect()->route('inedx');
    }
}
