<?php

namespace App\User;

use App\Auth\OAuth2Token;
use App\Freee\FreeeApi;
use App\Heroku\HerokuApi;

class UserAccountLinker
{
    private FreeeApi $freeeApi;
    private HerokuApi $herokuApi;
    private UserRepository $userRepository;

    public function __construct(
        FreeeApi $freeeApi,
        HerokuApi $herokuApi,
        UserRepository $userRepository
    ) {
        $this->freeeApi = $freeeApi;
        $this->herokuApi = $herokuApi;
        $this->userRepository = $userRepository;
    }

    public function getOrCreateUserWithFreeeToken(OAuth2Token $freeeToken)
    {
        $freeeUser = $this->freeeApi->getMe($freeeToken);
        $user = $this->userRepository->getUserByFreeeId($freeeUser->getId());

        if ($user === null) {
            // ユーザーレコードを作成
            $user = $this->userRepository->createUser();
            $this->userRepository->saveFreeeUserAndToken(
                $user,
                $freeeUser,
                $freeeToken
            );
        } else {
            // ユーザーはすでにあるので free の token だけ更新
            $this->userRepository->saveFreeeUserAndToken(
                $user,
                $freeeUser,
                $freeeToken
            );
        }
        return $user;
    }

    public function createHerokuLinkage(User $user, OAuth2Token $token)
    {
        $account = $this->herokuApi->getAccountFreatures($token);
        $this->userRepository->createHerokuLinkage(
            $user,
            $account->getId(),
            $token
        );
    }
}
