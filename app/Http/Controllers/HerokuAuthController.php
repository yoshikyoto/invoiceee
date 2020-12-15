<?php

namespace App\Http\Controllers;

use App\Auth\Heroku;
use App\User\UserAccountLinker;
use Illuminate\Http\Request;

class HerokuAuthController
{
    private Heroku $heroku;
    private UserAccountLinker $userAccountLinker;

    public function __construct(
        Heroku $heroku,
        UserAccountLinker $userAccountLinker
    ) {
        $this->heroku = $heroku;
        $this->userAccountLinker = $userAccountLinker;
    }

    public function callback(Request $request)
    {
        $code = $request->input('code');
        $token = $this->heroku->getToken($code);
        $user = $this->userAccountLinker->getOrCreateUserWithFreeeToken($token);
        $request->session()->put('userId', $user->getId());
        redirect('index');
    }
}
