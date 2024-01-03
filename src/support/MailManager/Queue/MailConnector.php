<?php

namespace Support\MailManager\Queue;

use Illuminate\Queue\Connectors\ConnectorInterface;
use Support\MailManager\Contracts\MailManagerInterface;
use Support\MailManager\Contracts\QueueStorageInterface;

class MailConnector implements ConnectorInterface
{
    public function __construct(
        private readonly MailManagerInterface $mailManager,
        private readonly QueueStorageInterface $queueStorage,
    ) {}

    public function connect(array $config)
    {
        return new MailQueue($this->mailManager, $this->queueStorage);
    }
}
