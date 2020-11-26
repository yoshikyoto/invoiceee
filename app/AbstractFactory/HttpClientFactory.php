<?php

namespace App\AbstractFactory;

use GuzzleHttp\Client;

class HttpClientFactory
{
    public function create(): Client
    {
        return new Client();
    }
}
