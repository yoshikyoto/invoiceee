<?php

namespace App\Http\Controllers;

use App\AbstractFactory\LoggerFactory;
use App\User\UserAccountLinker;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Auth\Freee;
use Psr\Log\LoggerInterface;

class FreeeAuthController extends Controller
{
    private Freee $freee;
    private UserAccountLinker $userAccountLiker;
    private LoggerInterface $logger;

    public function __construct(
        Freee $freee,
        UserAccountLinker $userAccountLiker,
        LoggerFactory $loggerFactory
    ) {
        $this->freee = $freee;
        $this->userAccountLiker = $userAccountLiker;
        $this->logger = $loggerFactory->create();
    }

    public function callback(Request $request)
    {
        $code = $request->input('code');
        $token = $this->freee->getToken($code);
        $user = $this->userAccountLiker->getOrCreateUserWithFreeeToken($token);
        $this->logger->info('セッションに userId をセットし、 index にリダイレクトします');
        $request->session()->put('userId', $user->getId());
        redirect('index');
    }
}
