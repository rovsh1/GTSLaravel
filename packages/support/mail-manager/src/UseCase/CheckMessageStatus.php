<?php

namespace Pkg\MailManager\UseCase;

use Pkg\MailManager\Contracts\QueueStorageInterface;
use Pkg\MailManager\Dto\QueueMessageStatusDto;
use Pkg\MailManager\ValueObject\MailId;
use Pkg\MailManager\ValueObject\QueueMailStatusEnum;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class CheckMessageStatus implements UseCaseInterface
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