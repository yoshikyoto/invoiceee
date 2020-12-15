<?php

namespace App\User;

use App\AbstractFactory\LoggerFactory;
use App\Auth\OAuth2Token;
use App\Freee\FreeeUser;
use App\Model\User as UserModel;
use App\Model\FreeeUser as FreeeUserModel;
use Psr\Log\LoggerInterface;

class UserRepository
{
    private LoggerInterface $logger;

    public function __construct(LoggerFactory $loggerFactory)
    {
        $this->logger = $loggerFactory->create();
    }

    public function get(int $id): ?User
    {
        $this->logger->info('ユーザー情報を取得します', [
            'id' => $id,
            'file' => __FILE__,
            'line' => __LINE__,
        ]);
        return User::find($id);
    }

    public function getUserByFreeeId(int $freeeUserId): ?User
    {
        $freeeUser = FreeeUserModel::getByFreeeUserId($freeeUserId);
        if ($freeeUser === null) {
            return null;
        }
        $user = UserModel::getById($freeeUser->getId());
        $this->logger->info('freee の user_id を元に DB からユーザーを取得', [
            'freeeUserId' => $freeeUserId,
            'invoiceeeUserId' => $freeeUser ? $freeeUser->getId() : null,
            'file' => __FILE__,
            'line' => __LINE__,
        ]);
        return $user;
    }

    public function createUser(): ?User
    {
        $this->logger->info('User DB にユーザーを作成します');
        return UserModel::createUser();
    }

    public function saveFreeeUserAndToken(
        User $user,
        FreeeUser $freeeUser,
        OAuth2Token $token
    ): void {
        $this->logger->info('freee の API token を DB に保存します', [
            'user' => $user,
            'freeeUserId' => $freeeUser->getId(),
            'freeeToken' => $token->getToken(),
        ]);
        FreeeUserModel::createFreeeUser(
            $user->getId(),
            $freeeUser->getId(),
            $token->getToken(),
        );
    }

    public function updateFreeeToken(User $user, OAuth2Token $token): FreeeUserModel
    {
        $this->logger->info('FreeUser DB の freeeToken を更新します', [
            'user' => $user->getId(),
            'freeeToken' => $token->getToken(),
        ]);
        return FreeeUserModel::updateToken($user->getId(), $token->getToken());
    }
}
