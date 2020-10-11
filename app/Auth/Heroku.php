<?php

namespace App\Auth;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class Heroku
{
    public function getAuthUrl(): string
    {
        $clientId = env('HEROKU_CLIENT_ID');
        $params = [
            'client_id' => $clientId,
            'response_type' => 'code',
            // 'scope' => 'read',
        ];
        $queryString = http_build_query($params);
        $baseUrl = 'https://id.heroku.com/oauth/authorize';
        return "{$baseUrl}?{$queryString}";
    }

    public function getToken(string $code): OAuth2Token
    {
        $client = new Client();
        $response = $client->post('https://id.heroku.com/oauth/token', [
            RequestOptions::FORM_PARAMS => [
                'grant_type' => 'authorization_code',
                'client_secret' => env('HEROKU_CLIENT_SECRET'),
                'code' => $code,
            ],
        ]);
        $json = json_decode($response->getBody()->getContents(), true);
        return new OAuth2Token($json['access_token']);
    }
}
