<?php

namespace Support\MailManager\UseCase;

use Support\MailManager\Contracts\MailManagerInterface;
use Support\MailManager\ValueObject\MailId;

class ResendMessage
{
    public function __construct(
        private readonly MailManagerInterface $mailManager,
    ) {}

    public function execute(string $messageUuid): void
    {
        $this->mailManager->resendMessage(new MailId($messageUuid));
    }
}