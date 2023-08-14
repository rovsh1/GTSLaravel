<?php

namespace Module\Support\MailManager\Application\UseCase;

use Module\Support\MailManager\Application\Dto\MailMessageDto;
use Module\Support\MailManager\Application\Factory\MailMessageFactory;
use Module\Support\MailManager\Domain\Service\QueueManagerInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class SendTemplate implements UseCaseInterface
{
    public function __construct(
        private readonly QueueManagerInterface $mailManager,
    ) {
    }

    public function execute(MailMessageDto $messageDto): string
    {
        $message = MailMessageFactory::fromDto($messageDto);
        $this->mailManager->sendSync($message, []);

        return $message->id()->value();
    }
}