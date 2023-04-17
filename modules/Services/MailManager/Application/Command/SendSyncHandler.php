<?php

namespace Module\Services\MailManager\Application\Command;

use Custom\Framework\Contracts\Bus\CommandHandlerInterface;
use Custom\Framework\Contracts\Bus\CommandInterface;
use Module\Services\MailManager\Domain\Factory\MailMessageFactory;
use Module\Services\MailManager\Domain\Service\QueueManagerInterface;

class SendSyncHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly QueueManagerInterface $mailManager,
    ) {
    }

    public function handle(CommandInterface|SendSync $command): string
    {
        $queueMessage = $this->mailManager->sendSync(
            MailMessageFactory::fromDto($command->mailMessage),
            $command->context
        );

        return $queueMessage->uuid;
    }
}
