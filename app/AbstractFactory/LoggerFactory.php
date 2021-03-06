<?php

namespace App\AbstractFactory;

use Illuminate\Support\Facades\Log;
use Psr\Log\LoggerInterface;

class LoggerFactory
{
    public function create(): LoggerInterface
    {
        // channel を指定しない場合は config/logging.php の
        // default に指定されているチャンネルになる
        return Log::channel();
    }

    public function createForConsole(): LoggerInterface
    {
        return Log::channel('file_and_console');
    }
}
