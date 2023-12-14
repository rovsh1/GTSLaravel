<?php

namespace Module\Support\MailManager\Infrastructure\Queue;

use Illuminate\Queue\Connectors\ConnectorInterface;
use Module\Support\MailManager\Domain\Service\MailManagerInterface;
use Module\Support\MailManager\Domain\Storage\QueueStorageInterface;

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
