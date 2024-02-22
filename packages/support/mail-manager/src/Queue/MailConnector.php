<?php

namespace Pkg\MailManager\Queue;

use Illuminate\Queue\Connectors\ConnectorInterface;
use Pkg\MailManager\Contracts\MailManagerInterface;
use Pkg\MailManager\Contracts\QueueStorageInterface;

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
