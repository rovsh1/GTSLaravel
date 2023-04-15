<?php

namespace Module\Services\MailManager\Infrastructure\Console\Commands;

use Illuminate\Console\Command;
use Module\Services\MailManager\Domain\Service\QueueManagerInterface;

class Queue extends Command
{
    protected $signature = 'mail:queue {action}';

    protected $description = '';

    public function __construct(
        private readonly QueueManagerInterface $queueManager,
    ) {
        parent::__construct();
    }

    public function handle()
    {
//        $this->queueManager->retryAll();
    }
}