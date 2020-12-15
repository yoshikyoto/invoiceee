<?php

namespace App\User;

use App\Auth\OAuth2Token;
use App\Freee\FreeeApi;

class UserAccountLinker
{
    private FreeeApi $freeeApi;
    private UserRepository $userRepository;

    public function __construct(
        FreeeApi $freeeApi,
        UserRepository $userRepository
    ) {
        $this->freeeApi = $freeeApi;
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
}
