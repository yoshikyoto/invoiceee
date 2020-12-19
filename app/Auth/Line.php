<?php

namespace App\Auth;

use App\AbstractFactory\LoggerFactory;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Psr\Log\LoggerInterface;

class Line
{
    private Client $client;
    private LoggerInterface $logger;

    /**
     * @param Client $client
     */
    public function __construct(
        Client $client,
        LoggerFactory $loggerFactory
    ) {
        $this->client = $client;
        $this->logger = $loggerFactory->create();
    }

    public function getAuthUrl(): string
    {
        $clientId = env('LINE_CLIENT_ID');
        $redirectUri = env('LINE_CALLBACK_URL');
        $params = [
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'response_type' => 'code',
            // TODO state はランダムで生成刷る必要がある
            'state' => 'test',
            'scope' => 'profile openid',
        ];
        $queryString = http_build_query($params);
        $baseUrl = 'https://access.line.me/oauth2/v2.1/authorize';
        return $baseUrl . '?' . $queryString;
    }

    public function getToken(string $code): LineToken
    {
        $uri = 'https://api.line.me/oauth2/v2.1/token';
        $options = [
            RequestOptions::FORM_PARAMS => [
                'grant_type' => 'authorization_code',
                'client_id' => env('LINE_CLIENT_ID'),
                'client_secret' => env('LINE_CLIENT_SECRET'),
                'code' => $code,
                'redirect_uri' => env('LINE_CALLBACK_URL'),
            ]
        ];
        $response = $this->client->post($uri, $options);
        $json = $response->getBody()->getContents();
        $this->logger->info('LINE API から Token を取得', [
            'requestUri' => $uri,
            'requestOptions' => $options,
            'responseBody' => $json,
        ]);
        $array = json_decode($json, true);
        return new LineToken(
            $array['access_token'],
            $array['id_token']
        );
    }
}
