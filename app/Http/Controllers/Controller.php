<?php

namespace App\Http\Controllers;

use App\AbstractFactory\LoggerFactory;
use App\Auth\Freee;
use App\Auth\Heroku;
use App\Auth\Line;
use App\User\UserRepository;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Psr\Log\LoggerInterface;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    private Freee $freee;
    private Heroku $heroku;
    private Line $line;
    private LoggerInterface $logger;
    private UserRepository $userRepository;

    public function __construct(
        Freee $freee,
        Heroku $heroku,
        Line $line,
        LoggerFactory $loggerFactory,
        UserRepository $userRepository
    ) {
        $this->freee = $freee;
        $this->heroku = $heroku;
        $this->line = $line;
        $this->logger = $loggerFactory->create();
        $this->userRepository = $userRepository;
    }

    public function index(Request $request)
    {
        $freeeAuthUrl = $this->freee->getAuthUrl();
        $herokuAuthUrl = $this->heroku->getAuthUrl();
        $lineAuthUrl = $this->line->getAuthUrl();

        $userId = $request->session()->get('userId');
        if ($userId === null) {
            $user = null;
            $linkages = null;
        } else {
            $user = $this->userRepository->get($userId);
            $linkages = $this->userRepository->getAllLinkagesFor($user);
        }

        return view('index', [
            'freeeAuthUrl' => $freeeAuthUrl,
            'herokuAuthUrl' => $herokuAuthUrl,
            'lineAuthUrl' => $lineAuthUrl,
            'user' => $user,
            'linkages' => $linkages,
        ]);
    }
}
