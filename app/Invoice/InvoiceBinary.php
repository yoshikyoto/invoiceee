<?php

namespace App\Invoice;

use Carbon\Carbon;

class InvoiceBinary
{
    const EXTENSION_HTML = 'html';

    /**
     * @var string サービス内で請求書を一位に特定できる名前で、保存する時のファイル名になる
     *  日時-サービス名-請求書id.拡張子 という名前を推奨
     */
    private string $name;
    private string $binary;

    /**
     * InvoiceBinary constructor.
     * @param string $name
     * @param string $binary
     */
    public function __construct(
        string $name,
        string $binary
    ) {
        $this->name = $name;
        $this->binary = $binary;
    }

    /**
     * 推奨される請求書の名前を生成する
     * @param Carbon $date 請求書が発行された日付
     * @param string $serviceName 請求書発行元のサービス名
     * @param string $invoiceId 請求書のID
     * @param string $extension 請求書の拡張子
     * @return string
     */
    public static function createName(
        Carbon $date,
        string $serviceName,
        string $invoiceId,
        string $extension
    ): string {

        $dateString = $date->format('Y-m-d');
        return "{$dateString}-{$serviceName}-{$invoiceId}.{$extension}";
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getBinary(): string
    {
        return $this->binary;
    }
}
