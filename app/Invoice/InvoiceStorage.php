<?php

namespace App\Invoice;

use Illuminate\Support\Facades\Storage;

class InvoiceStorage
{
    public function save(InvoiceBinary $invoiceBinary)
    {
        Storage::put(
            $invoiceBinary->getName(),
            $invoiceBinary->getBinary()
        );
    }
}
