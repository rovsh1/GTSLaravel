<?php

namespace Module\Support\MailManager\Application\UseCase;

use Module\Support\MailManager\Application\Factory\MailMessageFactory;
use Module\Support\MailManager\Domain\Service\QueueManagerInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Shared\Dto\Mail\SendMessageRequestDto;

class SendMessage implements UseCaseInterface
{
    public function __construct(
        private readonly QueueManagerInterface $mailManager,
    ) {
    }

    public function execute(SendMessageRequestDto $requestDto): string
    {
        $message = MailMessageFactory::fromDto($requestDto->message);

        if ($requestDto->async) {
            $this->mailManager->push($message, $priority = 0, $requestDto->context);
        } else {
            $this->mailManager->sendSync($message, $requestDto->context);
        }

        return $message->id()->value();
    }
}