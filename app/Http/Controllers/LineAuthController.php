<?php

namespace App\Http\Controllers;

use App\AbstractFactory\LoggerFactory;
use App\Auth\Line;
use Illuminate\Http\Request;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class LineAuthController
{
    private LoggerInterface $logger;
    private Line $line;

    public function __construct(
        LoggerFactory $loggerFactory,
        Line $line
    ) {
        $this->logger = $loggerFactory->create();
        $this->line = $line;
    }

    public function callback(Request $request)
    {
        $code = $request->input('code');
        $this->logger->log(
            LogLevel::INFO,
            '/line/callback',
            [
                'code' => $code
            ]
        );
        $token = $this->line->getToken($code);

    }
}
