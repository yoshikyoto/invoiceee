<?php

namespace App\Auth;

class Heroku
{
    public function getAuthUrl(): string
    {
        $clientId = env('HEROKU_CLIENT_ID');
        $params = [
            'client_id' => $clientId,
            'response_tyoe' => 'code',
        ];
        $queryString = http_build_query($params);
        $baseUrl = 'https://id.heroku.com/oauth/authorize';
        return "{$baseUrl}?{$queryString}";
    }
}
