<?php

namespace App\Auth;

class Freee
{
    public function getAuthUrl(): string
    {
        $clientId = env('FREEE_CLIENT_ID');
        $redirectUri = env('FREEE_CALBACK_URL');
        $params = [
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'response_type' => 'code',
        ];
        $queryString = http_build_query($params);
        $baseUrl = 'https://accounts.secure.freee.co.jp/public_api/authorize';
        return "{$baseUrl}?{$queryString}";
    }
}
