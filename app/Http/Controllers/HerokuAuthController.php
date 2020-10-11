<?php

namespace App\Http\Controllers;

use App\Auth\Heroku;
use Illuminate\Http\Request;

class HerokuAuthController
{
    private Heroku $heroku;

    public function __construct(Heroku $heroku)
    {
        $this->heroku = $heroku;
    }

    public function callback(Request $request)
    {
        $code = $request->input('code');
        var_dump($code);

        $token = $this->heroku->getToken($code);
        var_dump($token);
    }
}
