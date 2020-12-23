<?php

namespace App\Invoice;

use App\AbstractFactory\LoggerFactory;
use App\Model\Linkage;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Psr\Log\LoggerInterface;

class InvoiceCollector
{
    private LoggerInterface $logger;
    private InvoiceDownloaderFactory $invoiceDownloaderFactory;
    private InvoiceStorage $storage;

    /**
     * InvoiceCollector constructor.
     * @param LoggerFactory $loggerFactory
     * @param InvoiceDownloaderFactory $invoiceDownloaderFactory
     * @param InvoiceStorage $storage
     */
    public function __construct(
        LoggerFactory $loggerFactory,
        InvoiceDownloaderFactory $invoiceDownloaderFactory,
        InvoiceStorage $storage
    ) {
        $this->logger = $loggerFactory->createForConsole();
        $this->invoiceDownloaderFactory = $invoiceDownloaderFactory;
        $this->storage = $storage;
    }


    public function collect()
    {
        Linkage::chunk(
            100,
            function (Collection $linkages) {
                foreach ($linkages as $linkage) {
                    /**
                     * @var Linkage $linkage
                     */
                    $this->logger->info(
                        '以下のLinkageについて処理',
                        [
                            'userId' => $linkage->getUserId(),
                            'linkageServiceName' => $linkage->getServiceName(),
                            'linkageServiceAccountId' => $linkage->getServiceAccountId(),
                            'lastInvoiceDownloadedAt' => $linkage->getLastInvoiceDownloadedAt(),
                        ]
                    );

                    $downloader = $this->invoiceDownloaderFactory->create(
                        $linkage->getServiceName()
                    );
                    $invoices = $downloader->getInvoices($linkage->getOAuth2Token());

                    /**
                     * @var Carbon|null $maxInvoiceCreatedAt
                     */
                    $maxInvoiceCreatedAt = null;
                    foreach ($invoices as $invoice) {
                        $this->logger->info(
                            '請求書を処理します',
                            [
                                'createdAt' => $invoice->getCreatedAt(),
                            ]
                        );
                        // 必要であれば請求書をダウンロードする処理
                        if ($this->shouldDownloadInvoice($linkage, $invoice)) {
                            $this->logger->info('請求書をダウンロードします');
                            $invoiceBinary = $downloader->getInvoiceBinary($linkage->getOAuth2Token(), $invoice);
                            $this->logger->info(
                                '請求書を保存します',
                                [
                                    'name' => $invoiceBinary->getName(),
                                ]
                            );
                            $this->storage->save($invoiceBinary);
                            $this->logger->info('請求書を保存しました');
                            $maxInvoiceCreatedAt = $this->getMaxDateTime($maxInvoiceCreatedAt, $invoice->getCreatedAt());
                        }
                        // 次のファイルへ
                    }
                    // 必要であれば最終ダウンロード日時を更新する処理
                    if ($maxInvoiceCreatedAt !== null) {
                        $linkage->updateLastInvoiceDownloadedAt($maxInvoiceCreatedAt);
                        $this->logger->info(
                            '最終ダウンロード日時を更新',
                            [
                                'downloadedAt' => $maxInvoiceCreatedAt,
                            ]
                        );
                    }
                }
            }
        );
    }

    public function shouldDownloadInvoice(
        Linkage $linkage,
        Invoice $invoice
    ): bool {
        if ($linkage->hasInvoiceNeverDownloaded()) {
            return true;
        }
        // 最後にダウンロードした invoice の日時を保存しておくので、
        // gte ではなく gt で比較する必要がある
        return $invoice->getCreatedAt()->gt($linkage->getLastInvoiceDownloadedAt());
    }

    public function getMaxDateTime(
        ?Carbon $a,
        Carbon $b
    ): Carbon {
        if ($a === null) {
            return $b;
        }
        if ($a->gt($b)) {
            return $a;
        } else {
            return $b;
        }
    }
}
