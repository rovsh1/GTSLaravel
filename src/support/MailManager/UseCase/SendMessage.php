<?php

namespace Support\MailManager\UseCase;

use Sdk\Shared\Dto\Mail\MailMessageDto;
use Support\MailManager\Contracts\QueueStorageInterface;
use Support\MailManager\Factory\MailMessageFactory;

class SendMessage
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