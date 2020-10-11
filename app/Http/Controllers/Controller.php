<?php

namespace App\Http\Controllers;

use App\Auth\Freee;
use App\Auth\Heroku;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    private Freee $freee;

    private Heroku $heroku;

    public function __construct(
        Freee $freee,
        Heroku $heroku
    ) {
        $this->freee = $freee;
        $this->heroku = $heroku;
    }

    public function index()
    {
        $freeeAuthUrl = $this->freee->getAuthUrl();
        $herokuAuthUrl = $this->heroku->getAuthUrl();
        return view('welcome', [
            'freeeAuthUrl' => $freeeAuthUrl,
            'herokuAuthUrl' => $herokuAuthUrl,
        ]);
    }
}
