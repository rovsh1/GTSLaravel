<?php

namespace Module\Support\MailManager\Application\Command;

use Module\Support\MailManager\Domain\Factory\MailMessageFactory;
use Module\Support\MailManager\Domain\Service\QueueManagerInterface;
use Sdk\Module\Contracts\Bus\CommandHandlerInterface;
use Sdk\Module\Contracts\Bus\CommandInterface;

class SendTemplateSyncHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly QueueManagerInterface $mailManager,
    ) {
    }

    public function handle(CommandInterface|SendTemplateSync $command): bool
    {

        $this->mailManager->sendSync(MailMessageFactory::fromDto($command->mailMessage));

        return true;
    }
}
