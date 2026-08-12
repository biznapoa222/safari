<?php

namespace App\Console\Commands;

use App\Services\IncomingMailService;
use Illuminate\Console\Command;

class FetchIncomingMail extends Command
{
    protected $signature = 'mail:fetch';
    protected $description = 'Fetch new messages from every active IMAP/POP3 account';

    public function handle(IncomingMailService $service): int
    {
        $this->info('Fetching incoming mail...');
        $stats = $service->fetchAll();
        $this->line(sprintf('Accounts: %d, fetched: %d, errors: %d', $stats['accounts'], $stats['fetched'], $stats['errors']));
        return self::SUCCESS;
    }
}
