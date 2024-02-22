<?php

namespace Pkg\MailManager\UseCase;

use Pkg\MailManager\Contracts\MailManagerInterface;
use Pkg\MailManager\ValueObject\MailId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class ResendMessage implements UseCaseInterface
{
    public function __construct(
        private readonly MailManagerInterface $mailManager,
    ) {}

    public function execute(string $messageUuid): void
    {
        $this->mailManager->resendMessage(new MailId($messageUuid));
    }
}