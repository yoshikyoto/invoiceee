<?php

namespace App\Http\Controllers;

use App\AbstractFactory\LoggerFactory;
use App\Auth\Freee;
use App\Auth\Heroku;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Psr\Log\LoggerInterface;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    private Freee $freee;
    private Heroku $heroku;
    private LoggerInterface $logger;

    public function __construct(
        Freee $freee,
        Heroku $heroku,
        LoggerFactory $loggerFactory
    ) {
        $this->freee = $freee;
        $this->heroku = $heroku;
        $this->logger = $loggerFactory->create();
    }

    public function index()
    {
        $this->logger->info('/にアクセスがありました');
        $freeeAuthUrl = $this->freee->getAuthUrl();
        $herokuAuthUrl = $this->heroku->getAuthUrl();
        return view('welcome', [
            'freeeAuthUrl' => $freeeAuthUrl,
            'herokuAuthUrl' => $herokuAuthUrl,
        ]);
    }
}
