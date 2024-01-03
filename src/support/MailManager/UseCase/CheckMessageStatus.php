<?php

namespace Support\MailManager\UseCase;

use Support\MailManager\Contracts\QueueStorageInterface;
use Support\MailManager\Dto\QueueMessageStatusDto;
use Support\MailManager\ValueObject\MailId;
use Support\MailManager\ValueObject\QueueMailStatusEnum;

class CheckMessageStatus
{
    public function __construct(
        private readonly QueueStorageInterface $queueStorage,
    ) {}

    public function execute(string $messageUuid): QueueMessageStatusDto
    {
        $message = $this->queueStorage->find(new MailId($messageUuid));
        if (!$message) {
            return QueueMessageStatusDto::notFound($messageUuid);
        }

        return match ($message->status()) {
            QueueMailStatusEnum::FAILED => QueueMessageStatusDto::failed($messageUuid, $queueRecord->exception ?? ''),
            QueueMailStatusEnum::SENT => QueueMessageStatusDto::sent($messageUuid),
            default => QueueMessageStatusDto::waiting($messageUuid),
        };
    }
}