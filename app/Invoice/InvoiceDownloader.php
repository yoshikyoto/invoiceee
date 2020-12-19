<?php

namespace App\Invoice;

use App\Auth\OAuth2Token;

interface InvoiceDownloader
{
    /**
     * @param OAuth2Token $token
     * @return Invoice[]
     */
    public function getInvoices(OAuth2Token $token): array;


    public function getInvoiceBinary(
        OAuth2Token $token,
        Invoice $invoice
    ): InvoiceBinary;
}
