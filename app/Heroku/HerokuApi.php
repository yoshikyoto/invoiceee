<?php

namespace App\Heroku;

use App\AbstractFactory\HttpClientFactory;
use App\Auth\OAuth2Token;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Psr\Log\LoggerInterface;

class HerokuApi
{
    private Client $client;
    private LoggerInterface $logger;

    /**
     * HerokuApi constructor.
     * @param HttpClientFactory $clientFactory
     * @param LoggerInterface $logger
     */
    public function __construct(HttpClientFactory $clientFactory, LoggerInterface $logger)
    {
        $this->client = $clientFactory->create();
        $this->logger = $logger;
    }


    public function getAccountFreatures(OAuth2Token $token): HerokuAccount
    {
        $response = $this->client->get(
            'https://api.heroku.com/account/features',
            [
                RequestOptions::HEADERS => [
                    'Accept' => 'application/vnd.heroku+json; version=3',
                    'Authorization' => 'Bearer ' . $token->getToken(),
                ]
            ]
        );
        $json = json_decode($response->getBody()->getContents(), true);
        return new HerokuAccount($json[0]['id']);
    }
}
