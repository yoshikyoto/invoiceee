<?php

namespace App\Invoice;

use App\Auth\OAuth2Token;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

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

    /**
     * @param OAuth2Token $token
     * @param HerokuInvoice $invoice
     * @return string
     */
    public function getInvoiceHtml(OAuth2Token $token, HerokuInvoice $invoice): string
    {
        $response = $this->client->post(
            'https://particleboard.heroku.com/account/invoices/' . $invoice->getNumber(),
            [
                RequestOptions::FORM_PARAMS => [
                    'token' => $token->getToken(),
                ],
            ],
        );
        return $response->getBody()->getContents();
    }
}
