<?php

namespace App\Http\Controllers;

use App\AbstractFactory\LoggerFactory;
use App\Auth\Line;
use App\Http\UserResolver;
use App\User\UserAccountLinker;
use App\User\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class LineAccountController
{
    private LoggerInterface $logger;
    private UserResolver $userResolver;
    private UserRepository $userRepository;

    public function __construct(
        LoggerFactory $loggerFactory,
        UserResolver $userResolver,
        UserRepository $userRepository
    ) {
        $this->logger = $loggerFactory->create();
        $this->userResolver = $userResolver;
        $this->userRepository = $userRepository;
    }

    public function postLineAccount(Request $request)
    {
        $user = $this->userResolver->getUser($request);
        if ($user === null) {
            return $this->loginPage();
        }

        $lineUserId = $request->input('lineUserId');
        if ($lineUserId === null) {
            return $this->errorPage();
        }

        $this->userRepository->updateLineUserId($user, $lineUserId);
        return $this->successPage();
    }

    private function errorPage()
    {
        return redirect()->route('index');
    }

    private function loginPage()
    {
        return redirect()->route('index');
    }

    public function successPage()
    {
        return redirect()->route('index');
    }
}
