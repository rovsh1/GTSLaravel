<?php

namespace Module\Services\MailManager\Application\Command;

use Custom\Framework\Contracts\Bus\CommandHandlerInterface;
use Custom\Framework\Contracts\Bus\CommandInterface;
use Module\Services\MailManager\Domain\Factory\MailMessageFactory;
use Module\Services\MailManager\Domain\Service\QueueManagerInterface;

class SendQueuedHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly QueueManagerInterface $queueManager
    ) {
    }

    public function handle(CommandInterface|SendQueued $command): string
    {
        $queueMessage = $this->queueManager->push(
            MailMessageFactory::fromDto($command->mailMessage),
            $command->priority
        );

        $this->queueManager->sendWaitingMessages();

        return $queueMessage->uuid;
    }
}
