<?php

namespace Tests\Unit\Auth;

use PHPUnit\Framework\TestCase;
use App\Auth\Freee;

class FreeeTest extends TestCase
{
    public function 認証のURLを正常に生成できる()
    {
        $freee = new Freee();
        var_dump($freee->getAuthUrl());
    }
}
