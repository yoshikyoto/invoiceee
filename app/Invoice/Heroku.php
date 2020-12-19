<?php

namespace App\Invoice;

use App\AbstractFactory\HttpClientFactory;
use App\Auth\OAuth2Token;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class Heroku implements InvoiceDownloader
{
    private Client $client;

    public function __construct(HttpClientFactory $clientFactory)
    {
        $this->client = $clientFactory->create();
    }

    /**
     * @param OAuth2Token $token
     * @return HerokuInvoice[]
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getInvoices(OAuth2Token $token): array
    {
        $response = $this->client->get(
            'https://api.heroku.com/account/invoices',
            [
                RequestOptions::HEADERS => [
                    // AcceptパラメータでAPIのバージョンを指定する
                    'Accept' => 'application/vnd.heroku+json; version=3',
                    'Authorization' => 'Bearer ' . $token->getToken(),
                ],
            ]
        );
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
     * TODO ここのバイナリ取得あたりなんかいい感じにならないか
     * @param Invoice $invoice
     * @return InvoiceBinary
     */
    public function getInvoiceBinary(
        OAuth2Token $token,
        Invoice $invoice
    ): InvoiceBinary {
        if (!($invoice instanceof HerokuInvoice)) {
            $expected = HerokuInvoice::class;
            $actual = get_class($invoice);
            throw new \LogicException(
                __METHOD__ . " {$expected} が渡されるべきだが {$actual} が来た"
            );
        }
        $response = $this->client->post(
            'https://particleboard.heroku.com/account/invoices/' . $invoice->getNumber(),
            [
                RequestOptions::FORM_PARAMS => [
                    'token' => $token->getToken(),
                ],
            ],
        );
        return new InvoiceBinary(
            InvoiceBinary::createName(
                $invoice->getCreatedAt(),
                'heroku',
                $invoice->getId(),
                InvoiceBinary::EXTENSION_HTML
            ),
            $response->getBody()->getContents()
        );
    }
}
