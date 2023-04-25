<?php

namespace Module\Services\MailManager\Application\Job;

use Illuminate\Contracts\Queue\ShouldQueue;
use Module\Services\MailManager\Domain\Service\QueueManagerInterface;

class SendMail implements ShouldQueue
{
    public function __construct(
        public readonly int $id
    ) {
    }

    public function handle(QueueManagerInterface $queueManager)
    {
    }
}