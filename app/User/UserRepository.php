<?php

namespace App\User;

use App\AbstractFactory\LoggerFactory;
use App\Auth\OAuth2Token;
use App\Freee\FreeeUser as FreeeUserFromApi;
use App\Model\LineUser;
use App\Model\Linkage;
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
        return UserModel::find($id);
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

    public function createOrUpdateFreeeUser(
        User $user,
        FreeeUserFromApi $freeeUser,
        OAuth2Token $token
    ): void {
        $this->logger->info(
            '必要であれば freee_user のレコードを作成し、 freee の API token を DB に保存します',
            [
                'user' => $user,
                'freeeUserId' => $freeeUser->getId(),
                'freeeToken' => $token->getToken(),
            ]
        );
        FreeeUserModel::createOrUpdateFreeeUser(
            $user->getId(),
            $freeeUser->getId(),
            $token->getToken(),
        );
    }

    public function updateLineUserId(User $user, string $lineUserId)
    {
        $this->logger->info('LineUser DB に lineUserId を保存', [
            'userId' => $user->getId(),
            'lineUserId' => $lineUserId,
        ]);
        LineUser::updateLineUserId($user->getId(), $lineUserId);
    }

    public function createHerokuLinkage(User $user, string $herokuUserId, OAuth2Token $token)
    {
        Linkage::createHeroku($user->getId(), $herokuUserId, $token->getToken());
    }

    /**
     * @param User $user
     * @return Linkage[] Linkage の Collection
     */
    public function getAllLinkagesFor(User $user)
    {
        return Linkage::getAllLinkagesFor($user->getId());
    }
}
