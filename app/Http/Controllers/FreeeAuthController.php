<?php

namespace App\Http\Controllers;

use App\User\UserAccountLinker;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Auth\Freee;

class FreeeAuthController extends Controller
{
    private Freee $freee;
    private UserAccountLinker $userAccountLiker;

    public function __construct(
        Freee $freee,
        UserAccountLinker $userAccountLiker
    ) {
        $this->freee = $freee;
        $this->userAccountLiker = $userAccountLiker;
    }

    public function callback(Request $request)
    {
        $code = $request->input('code');
        var_dump($code);
        $token = $this->freee->getToken($code);
        var_dump($token);
        $user = $this->userAccountLiker->getOrCreateUserWithFreeeToken($token);
        session('userId', $user->getId());
    }
}
