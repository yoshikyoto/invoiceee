<?php

namespace App\Model;

use App\Auth\OAuth2Token;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Linkage extends Model
{
    protected $guarded = [
        'created_at',
        'updated_at',
    ];

    /**
     * 日付を Carbon に変形する属性
     * @var string[]
     */
    protected $dates = [
        'last_invoice_downloaded_at',
    ];

    const HEROKU = 'heroku';

    public function getUserId(): int
    {
        return $this->id;
    }

    public function getServiceAccountId(): string
    {
        return $this->service_account_id;
    }

    public function getServiceName(): string
    {
        return $this->service_name;
    }

    public function getOAuth2Token(): OAuth2Token
    {
        return new OAuth2Token($this->api_access_token);
    }

    public function getLastInvoiceDownloadedAt(): ?Carbon
    {
        return $this->last_invoice_downloaded_at;
    }

    public function hasInvoiceNeverDownloaded()
    {
        return $this->getLastInvoiceDownloadedAt() === null;
    }

    public function updateLastInvoiceDownloadedAt(Carbon $downloadedAt)
    {
        $this->last_invoice_downloaded_at = $downloadedAt;
        $this->save();
    }

    public static function createHeroku(
        int $id,
        string $herokuAccountId,
        string $token
    ) {
        static::create([
            'id' => $id,
            'service_name' => static::HEROKU,
            'service_account_id' => $herokuAccountId,
            'api_access_token' => $token,
        ]);
    }

    /**
     * $id に対応するユーザーの Linkage をすべて取得する
     * @param int $id
     * @return Linkage[] Linkage の Collection
     */
    public static function getAllLinkagesFor(int $id)
    {
        return static::where('id', $id)->get();
    }

    /**
     * 全ユーザーの Linkage を取得する
     * @param int $count チャンクのサイズ
     * @param callable $callback
     * @return bool
     */
    public static function chunk(int $count, callable $callback)
    {
        return static::query()->chunk($count, $callback);
    }
}
