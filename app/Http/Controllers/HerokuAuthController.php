<?php

namespace App\Http\Controllers;

use App\Auth\Heroku;
use App\Http\UserResolver;
use App\User\UserAccountLinker;
use App\User\UserRepository;
use Illuminate\Http\Request;

class HerokuAuthController
{
    private Heroku $heroku;
    private UserResolver $userResolver;
    private UserAccountLinker $userAccountLinker;

    public function __construct(
        Heroku $heroku,
        UserResolver $userResolver,
        UserAccountLinker $userAccountLinker
    ) {
        $this->heroku = $heroku;
        $this->userResolver = $userResolver;
        $this->userAccountLinker = $userAccountLinker;
    }

    public function callback(Request $request)
    {
        $user = $this->userResolver->getUser($request);
        if ($user === null) {
            return $this->loginPage();
        }

        $code = $request->input('code');
        $token = $this->heroku->getToken($code);
        $this->userAccountLinker->createHerokuLinkage($user, $token);
        return $this->successPage();
    }

    private function loginPage()
    {
        return redirect()->route('index');
    }

    private function successPage()
    {
        return redirect()->route('index');
    }
}
