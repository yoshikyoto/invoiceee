<?php

namespace App\Freee;

use App\AbstractFactory\HttpClientFactory;
use App\AbstractFactory\LoggerFactory;
use App\Auth\OAuth2Token;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Psr\Log\LoggerInterface;

class FreeeApi
{
    private Client $client;
    private LoggerInterface $logger;

    public function __construct(
        HttpClientFactory $httpClientFactory,
        LoggerFactory $loggerFactory
    ) {
        $this->client = $httpClientFactory->create();
        $this->logger = $loggerFactory->create();
    }

    public function getMe(OAuth2Token $token): FreeeUser
    {
        $uri = 'https://api.freee.co.jp/api/1/users/me';
        $options = [
            RequestOptions::HEADERS => [
                'Authorization' => "Bearer {$token->getToken()}",
            ],
        ];
        $response = $this->client->get($uri, $options);
        $json = $response->getBody()->getContents();
        $this->logger->info('freeeのAPIからユーザー情報を取得しました', [
            'requestUri' => $uri,
            'requestOptions' => $options,
            'responseBody' => $json,
        ]);
        $array = json_decode($json, true);
        return new FreeeUser($array['user']['id']);
    }
}
