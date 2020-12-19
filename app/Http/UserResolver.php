<?php

namespace App\Http;

use App\User\User;
use App\User\UserRepository;
use Illuminate\Http\Request;
use Psr\Log\LoggerInterface;

class UserResolver
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

    public function getUser(Request $request): ?User
    {
        $userId = $request->session()->get('userId');
        $user = $this->getUserFromSession($userId);
        $this->log($userId, $user);
        return $user;
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
        $this->logger->info('Http.UserResolver', [
            'userId' => $userId,
            'user' => $user,
        ]);
    }

    private function redirectToLoginPage()
    {
        return redirect()->route('inedx');
    }
}
