<?php

namespace Tests\Feature;

use App\AbstractFactory\HttpClientFactory;
use App\AbstractFactory\LoggerFactory;
use App\Auth\OAuth2Token;
use App\User\UserAccountLinker;
use App\User\UserRepository;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\App;
use Psr\Log\LoggerInterface;
use Tests\TestCase;
use Mockery;
use Mockery\MockInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AccountLinkTest extends TestCase
{
    use RefreshDatabase;

    private Client $guzzleClient;
    private LoggerInterface $logger;
    private UserRepository $userRepository;

    /**
     * プロパティにする必要あんまり無いけど、型がつけられるのでプロパティにする
     * @var UserAccountLinker
     */
    private UserAccountLinker $userAccountLinker;

    private function requestOptionsWithTokenHeader(string $token): array
    {
        return [
            RequestOptions::HEADERS => [
                'Authorization' => 'Bearer ' . $token,
            ]
        ];
    }

    private function responseMockWithBody(string $body): Response
    {
        return Mockery::mock(Response::class, [
            'getBody->getContents' => $body,
        ]);
    }

    /**
     * HttpClientFactory が $client を返すようにモックする
     * @param Client $client
     */
    private function mockHttpClientFactoryToReturn(Client $client): void
    {
        $this->mock(
            HttpClientFactory::class,
            function (MockInterface $mock) use ($client) {
                $mock->shouldReceive('create')
                    ->andReturn($client);
            }
        );
    }

    private function mockLoggerFactoryToReturn(LoggerInterface $logger): void
    {
        $this->mock(
            LoggerFactory::class,
            function (MockInterface $mock) use ($logger) {
                $mock->shouldReceive('create')
                    ->andReturn($logger);
            }
        );
    }

    /**
     * ログのメソッドをモックするが、ログで出力される中身は確認しない
     */
    private function mockLoggerMethodButNotCheckLogContent(): void
    {
        $this->logger->shouldReceive('info');
    }

    public function setUp(): void {
        parent::setUp();
        // GuzzleClient のモック
        $this->guzzleClient = Mockery::mock(Client::class);
        $this->mockHttpClientFactoryToReturn($this->guzzleClient);

        // Logger のモック
        $this->logger = Mockery::mock(LoggerInterface::class);
        $this->mockLoggerFactoryToReturn($this->logger);

        $this->userRepository = App::make(UserRepository::class);
    }

    /**
     * @test
     */
    public function freeeとの連携でユーザー作成とログインが行える()
    {
        // freee API リクエストが正常に呼び出されることを確認
        $this->guzzleClient->shouldReceive('get')
            ->once()
            ->with(
                'https://api.freee.co.jp/api/1/users/me',
                $this->requestOptionsWithTokenHeader('tokentokentoken')
            )->andReturn($this->responseMockWithBody(
                '{"user":{"id":"123456"}}'
            ));

        $this->mockLoggerMethodButNotCheckLogContent();

        // UserAccountLinker を動かす
        $token = new OAuth2Token('tokentokentoken');
        $this->userAccountLinker = App::make(UserAccountLinker::class);
        $user = $this->userAccountLinker->getOrCreateUserWithFreeeToken($token);

        // 初めての freee 連携なのでアカウントが作成されている
        $this->assertDatabaseHas('users', [
            'id' => $user->getId(),
        ]);
        $this->assertDatabaseHas('freee_users', [
            'id' => $user->getId(),
            'freee_user_id' => '123456',
            'freee_token' => 'tokentokentoken',
        ]);

        // もう一回 freee と OAuth 連携すると、
        // invoiceee のアカウントはすでに存在しているので、
        // token が更新されるだけになる
        $this->guzzleClient->shouldReceive('get')
            ->once()
            ->with(
                'https://api.freee.co.jp/api/1/users/me',
                $this->requestOptionsWithTokenHeader('new_token')
            )->andReturn($this->responseMockWithBody(
                '{"user":{"id":"123456"}}'
            ));
        $newToken = new OAuth2Token('new_token');
        $this->userAccountLinker->getOrCreateUserWithFreeeToken($newToken);
        $this->assertDatabaseHas('freee_users', [
            'id' => $user->getId(),
            'freee_user_id' => '123456',
            'freee_token' => 'new_token',
        ]);
    }
}
