<?php

namespace App\Http\Controllers;

use App\Auth\OAuth2Token;
use App\Invoice\Heroku;

class HerokuInvoiceController
{
    private Heroku $heroku;

    public function __construct(Heroku $heroku)
    {
        $this->heroku = $heroku;
    }

    /**
     * 領収書一覧を表示するだけ
     */
    public function list()
    {
        $token = new OAuth2Token('c0036dad-9973-4de3-a91c-75cf2fcbff0a');
        $invoices = $this->heroku->getInvoices($token);
        return view('invoice_heroku', [
            'invoices' => $invoices,
        ]);
    }
}
