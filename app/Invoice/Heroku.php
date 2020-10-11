<?php

namespace App\Invoice;

use App\Auth\OAuth2Token;
use Carbon\Carbon;
use GuzzleHttp\Client;

class Heroku
{
    /**
     * @param OAuth2Token $token
     * @return HerokuInvoice[]
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getInvoices(OAuth2Token $token): array
    {
        $client = new Client();
        $response = $client->get(
            'https://api.heroku.com/account/invoices', [
            'headers' => [
                // AcceptパラメータでAPIのバージョンを指定する
                'Accept' => 'application/vnd.heroku+json; version=3',
                'Authorization' => 'Bearer ' . $token->getToken(),
            ],
        ]);
        $json = json_decode($response->getBody()->getContents(), true);

        $invoices = [];
        foreach ($json as $item) {
            $invoices[] = new HerokuInvoice(
                $item['id'],
                $item['number'],
                new Carbon($item['period_end'])
            );
        }
        return $invoices;
    }
}
