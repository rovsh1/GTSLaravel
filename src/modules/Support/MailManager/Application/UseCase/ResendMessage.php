<?php

namespace Module\Support\MailManager\Application\UseCase;

use Module\Support\MailManager\Domain\Service\QueueManagerInterface;
use Module\Support\MailManager\Domain\ValueObject\MailId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class ResendMessage implements UseCaseInterface
{
    public function __construct(
        private readonly QueueManagerInterface $mailManager,
    ) {
    }

    public function execute(string $messageUuid): void
    {
        $this->mailManager->resendMessage(new MailId($messageUuid));
    }
}