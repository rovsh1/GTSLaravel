<?php

namespace Pkg\MailManager\UseCase;

use Pkg\MailManager\Contracts\QueueStorageInterface;
use Pkg\MailManager\Factory\MailMessageFactory;
use Sdk\Shared\Dto\Mail\MailMessageDto;

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