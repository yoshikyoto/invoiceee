<?php

namespace App\User;

use App\Auth\Freee;
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
            $user = $this->userRepository->createUser($freeeUser);
        }
        $user = $this->userRepository->saveFreeeToken($user, $freeeToken);
        return $user;
    }
}
