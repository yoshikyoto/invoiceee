<?php

namespace App\Auth;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class Freee
{
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

    public function getToken(string $code)
    {
        $client = new Client();
        $response = $client->post('https://accounts.secure.freee.co.jp/public_api/token', [
            RequestOptions::FORM_PARAMS => [
                'grant_type' => 'authorization_code',
                'client_id' => env('FREEE_CLIENT_ID'),
                'client_secret' => env('FREEE_CLIENT_SECRET'),
                'code' => $code,
                'redirect_uri' => env('FREEE_CALLBACK_URL'),
            ],
        ]);
        $json = json_decode($response->getBody()->getContents(), true);
        return new FreeeToken($json['access_token']);
    }
}
