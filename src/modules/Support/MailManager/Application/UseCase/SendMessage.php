<?php

namespace Module\Support\MailManager\Application\UseCase;

use Module\Support\MailManager\Application\Factory\MailMessageFactory;
use Module\Support\MailManager\Domain\Storage\QueueStorageInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Shared\Dto\Mail\MailMessageDto;

class SendMessage implements UseCaseInterface
{
    public function __construct(
        private readonly QueueStorageInterface $queueStorage,
    ) {}

    public function execute(MailMessageDto $messageDto): string
    {
        $message = MailMessageFactory::fromDto($messageDto);

//        if ($requestDto->async) {
        $this->queueStorage->push($message, $priority = 0);
//        } else {
//            $this->mailManager->sendSync($message);
//        }

        return $message->id()->value();
    }
}