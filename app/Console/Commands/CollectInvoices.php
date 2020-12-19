<?php

namespace App\Console\Commands;

use App\Invoice\InvoiceCollector;
use Illuminate\Console\Command;

class CollectInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice:collect';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '請求書ファイルを収集します';

    private InvoiceCollector $collector;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(InvoiceCollector $collector)
    {
        parent::__construct();
        $this->collector = $collector;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('請求書ファイルを収集します');
        $this->collector->collect();
        return 0;
    }
}
