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
        $freeeUserFromApi = $this->freeeApi->getMe($freeeToken);
        $user = $this->userRepository->getUserByFreeeId($freeeUserFromApi->getId());

        if ($user === null) {
            // ユーザーレコードを作成
            $user = $this->userRepository->createUser();
        }

        $this->userRepository->createOrUpdateFreeeUser(
            $user,
            $freeeUserFromApi,
            $freeeToken
        );
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
