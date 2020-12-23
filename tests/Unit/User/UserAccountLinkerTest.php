<?php

namespace Tests\Unit\User;

use App\Auth\OAuth2Token;
use App\Freee\FreeeApi;
use App\Freee\FreeeUser;
use App\Heroku\HerokuApi;
use App\User\User;
use App\User\UserAccountLinker;
use App\User\UserRepository;
use Tests\TestCase;
use Mockery;

class UserAccountLinkerTest extends TestCase
{
    private FreeeApi $freeeApi;
    private HerokuApi $herokuApi;
    private UserRepository $userRepository;
    private UserAccountLinker $userAccountLinker;

    public function setUp(): void
    {
        parent::setUp();
        $this->freeeApi = Mockery::mock(FreeeApi::class);
        $this->herokuApi = Mockery::mock(HerokuApi::class);
        $this->userRepository = Mockery::mock(UserRepository::class);
        $this->userAccountLinker = new UserAccountLinker(
            $this->freeeApi,
            $this->herokuApi,
            $this->userRepository
        );
    }

    /**
     * @test
     */
    public function Userが無い場合は作成したうえでFreeeの情報を保存()
    {
        $token = new OAuth2Token('freee_api_token');
        $freeeUserFromApi = Mockery::mock(FreeeUser::class, [
            'getId' => '123456',
        ]);
        $this->freeeApi->shouldReceive('getMe')
            ->once()
            ->with($token)
            ->andReturn($freeeUserFromApi);

        // ユーザーが無いので null を返す
        $this->userRepository->shouldReceive('getUserByFreeeId')
            ->once()
            ->with('123456')
            ->andReturn(null);

        // ユーザーが作成される
        $user = Mockery::mock(User::class);
        $this->userRepository->shouldReceive('createUser')
            ->once()
            ->andReturn($user);

        // freee の情報が保存される
        $this->userRepository->shouldReceive('createOrUpdateFreeeUser')
            ->with($user, $freeeUserFromApi, $token)
            ->once();

        $result = $this->userAccountLinker->getOrCreateUserWithFreeeToken($token);
        $this->assertSame($user, $result);
    }

    /**
     * @test
     */
    public function Userがある場合はレコードは作成せずfreeeの情報だけ更新する()
    {
        $token = new OAuth2Token('freee_api_token');
        $freeeUserFromApi = Mockery::mock(FreeeUser::class, [
            'getId' => '123456',
        ]);
        $this->freeeApi->shouldReceive('getMe')
            ->once()
            ->with($token)
            ->andReturn($freeeUserFromApi);

        // ユーザーがすでにいる
        $user = Mockery::mock(User::class);
        $this->userRepository->shouldReceive('getUserByFreeeId')
            ->once()
            ->with('123456')
            ->andReturn($user);

        // ユーザーの作成は呼ばれない
        $this->userRepository->shouldReceive('createUser')
            ->never();

        // freee の情報が保存される
        $this->userRepository->shouldReceive('createOrUpdateFreeeUser')
            ->with($user, $freeeUserFromApi, $token)
            ->once();

        $result = $this->userAccountLinker->getOrCreateUserWithFreeeToken($token);
        $this->assertSame($user, $result);
    }
}
