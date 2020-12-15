<?php

namespace Tests\Feature;

use App\AbstractFactory\HttpClientFactory;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\RequestOptions;
use Tests\TestCase;
use Mockery;
use Mockery\MockInterface;

class AccountLinkTest extends TestCase
{
    private Client $guzzleClient;

    /**
     * @test
     */
    public function Freeeアカウントのリンクができる()
    {
        // Freee への HTTP リクエストの部分をモック
        $this->instance(
            HttpClientFactory::class,
            Mockery::mock(
                HttpClientFactory::class,
                function (MockInterface $mock) {
                    $this->guzzleClient = Mockery::mock(Client::class);
                    $mock->shouldReceive('create')
                        ->andReturn($this->guzzleClient);

                    $uri = 'https://api.freee.co.jp/api/1/users/me';
                    $options = [
                        RequestOptions::HEADERS => [
                            'Authorization' => 'Bearer tokentokentoken',
                        ]
                    ];
                    $responseBody = <<<JSON
{
  "access_token":"tokentokentoken"
}
JSON;
                    $response = Mockery::mock(Response::class, [
                        'getBody->getContents' => $responseBody,
                    ]);
                    // TODO 呼び出し回数をアサーションする
                    $this->guzzleClient->shouldReceive('get')
                        ->with($uri, $options)
                        ->andReturn($response = Mockery::mock(Response::class));
            })
        );

        // TODO ログをモック

        $response = $this->get('/freee/callback?code=codecodecode');
        // $response->dump();
        $response->assertStatus(200);
    }

    public function guzzleClientShouldReceiveFreeeTokenWithCode()
    {
        $uri = 'https://accounts.secure.freee.co.jp/public_api/token';
        // TODO ちゃんと env をモックしてやる
        $options = Mockery::any();
    }
}
