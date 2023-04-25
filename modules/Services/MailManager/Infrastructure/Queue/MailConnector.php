<?php

namespace Module\Services\MailManager\Infrastructure\Queue;

use Illuminate\Queue\Connectors\ConnectorInterface;
use Module\Services\MailManager\Domain\Service\QueueManagerInterface;

class MailConnector implements ConnectorInterface
{
    public function __construct(
        private readonly QueueManagerInterface $mailManager
    ) {
    }

    public function connect(array $config)
    {
        return new MailQueue(
            $this->mailManager,
            //$config['table'],
            $config['queue'],
            $config['retry_after'] ?? 60,
            $config['after_commit'] ?? null
        );
    }
}
