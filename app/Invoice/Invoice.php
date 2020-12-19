<?php

namespace App\Invoice;

use Carbon\Carbon;

interface Invoice
{
    public function getCreatedAt(): Carbon;
}
