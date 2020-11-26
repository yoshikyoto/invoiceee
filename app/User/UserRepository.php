<?php

namespace App\User;

use App\AbstractFactory\LoggerFactory;
use App\Auth\OAuth2Token;
use App\Freee\FreeeUser;
use App\Model\User as UserModel;
use Psr\Log\LoggerInterface;

class UserRepository
{
    private LoggerInterface $logger;

    public function __construct(LoggerFactory $loggerFactory)
    {
        $this->logger = $loggerFactory->create();
    }

    public function getUserByFreeeId(int $freeeUserId): ?User
    {
        $user = UserModel::where('freee_user_id', $freeeUserId)->first();
        $this->logger->info(__METHOD__, [
            'freeeUserId' => $freeeUserId,
            'fetchdUser' => $user,
        ]);
        return $user;
    }

    public function createUser(FreeeUser $freeeUser): ?User
    {
        $this->logger->info(__METHOD__, [
            'freeeUesrId' => $freeeUser->getId(),
        ]);
        return UserModel::create([
            'freee_user_id' => $freeeUser->getId(),
        ]);
    }

    public function saveFreeeToken(
        User $user,
        OAuth2Token $token
    ): ?User {
        $this->logger->info(__METHOD__, [
            'user' => $user,
            'freeeToken' => $token->getToken(),
        ]);
        $user->freee_token = $token->getToken();
        $user->save();
        return $user;
    }
}
