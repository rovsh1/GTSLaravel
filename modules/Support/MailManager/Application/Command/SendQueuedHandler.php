<?php

namespace Module\Support\MailManager\Application\Command;

use Module\Support\MailManager\Domain\Factory\MailMessageFactory;
use Module\Support\MailManager\Domain\Service\QueueManagerInterface;
use Sdk\Module\Contracts\Bus\CommandHandlerInterface;
use Sdk\Module\Contracts\Bus\CommandInterface;

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
            $command->priority,
            $command->context
        );

        return $queueMessage->uuid;
    }
}
