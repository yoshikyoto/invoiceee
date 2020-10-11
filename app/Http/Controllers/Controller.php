<?php

namespace App\Http\Controllers;

use App\Auth\Freee;
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

    public function __construct(Freee $freee)
    {
        $this->freee = $freee;
    }

    public function index()
    {
        $freeeAuthUrl = $this->freee->getAuthUrl();
        return view('welcome', [
            'freeeAuthUrl' => $freeeAuthUrl,
        ]);
    }
}
