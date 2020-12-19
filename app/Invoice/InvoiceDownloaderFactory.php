<?php

namespace App\Invoice;

use App\Model\Linkage;

/**
 * Linkage の service_name に対応する Downloader を返す
 */
class InvoiceDownloaderFactory
{
    private Heroku $heroku;

    /**
     * @param Heroku $heroku
     */
    public function __construct(
        Heroku $heroku
    ) {
        $this->heroku = $heroku;
    }


    public function create(string $serviceName): InvoiceDownloader
    {
        switch ($serviceName) {
            case Linkage::HEROKU:
                return $this->heroku;
            default:
                throw new \LogicException(
                    'serviceName に対応する InvoiceDownloader が設定されていません'
                );
        }
    }
}
