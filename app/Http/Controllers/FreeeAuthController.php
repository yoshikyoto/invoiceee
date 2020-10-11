<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Auth\Freee;

class FreeeAuthController extends Controller
{
    private Freee $freee;

    public function __construct(Freee $freee)
    {
        $this->freee = $freee;
    }

    public function callback(Request $request)
    {
        $code = $request->input('code');
        var_dump($code);
        $token = $this->freee->getToken($code);
        var_dump($token);
    }
}
