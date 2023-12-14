<?php

namespace Module\Support\MailManager\Infrastructure\Queue;

use Illuminate\Queue\Connectors\ConnectorInterface;
use Module\Support\MailManager\Domain\Service\QueueManagerInterface;

class MailConnector implements ConnectorInterface
{
    public function __construct(
        private readonly QueueManagerInterface $mailManager
    ) {}

    public function connect(array $config)
    {
        return new MailQueue($this->mailManager);
    }
}
