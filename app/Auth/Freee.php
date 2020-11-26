<?php

namespace App\Auth;

use App\AbstractFactory\LoggerFactory;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Psr\Log\LoggerInterface;

class Freee
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerFactory $loggerFactory)
    {
        $this->logger = $loggerFactory->create();
    }

    public function getAuthUrl(): string
    {
        $clientId = env('FREEE_CLIENT_ID');
        $redirectUri = env('FREEE_CALLBACK_URL');
        $params = [
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'response_type' => 'code',
        ];
        $queryString = http_build_query($params);
        $baseUrl = 'https://accounts.secure.freee.co.jp/public_api/authorize';
        return "{$baseUrl}?{$queryString}";
    }

    public function getToken(string $code): OAuth2Token
    {
        $client = new Client();
        $uri = 'https://accounts.secure.freee.co.jp/public_api/token';
        $options = [
            RequestOptions::FORM_PARAMS => [
                'grant_type' => 'authorization_code',
                'client_id' => env('FREEE_CLIENT_ID'),
                'client_secret' => env('FREEE_CLIENT_SECRET'),
                'code' => $code,
                'redirect_uri' => env('FREEE_CALLBACK_URL'),
            ],
        ];
        $response = $client->post($uri, $options);
        $json = $response->getBody()->getContents();
        $this->logger->info('freeeのAPIからTokenを取得しました', [
            'requestUri' => $uri,
            'requestOptions' => $options,
            'responseBody' => $json,
        ]);
        $array = json_decode($json, true);
        return new OAuth2Token($array['access_token']);
    }
}
