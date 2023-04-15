<?php

namespace Module\Services\MailManager\Infrastructure\Console\Commands;

use Illuminate\Console\Command;
use Module\Services\MailManager\Domain\Service\QueueManagerInterface;

class RunDaemon extends Command
{
    private const WAITING_TIMEOUT = 5;

    protected $signature = 'mail:daemon';

    protected $description = 'Mail daemon';

    public function __construct(
        private readonly QueueManagerInterface $queueManager,
    ) {
        parent::__construct();
    }

    public function handle()
    {
        do {
            $this->queueManager->sendWaitingMessages();

            sleep(self::WAITING_TIMEOUT);
        } while (true);
    }
}