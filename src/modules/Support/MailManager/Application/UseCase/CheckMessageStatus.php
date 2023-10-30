<?php

namespace Module\Support\MailManager\Application\UseCase;

use Module\Support\MailManager\Application\Dto\QueueMessageStatusDto;
use Module\Support\MailManager\Domain\Repository\QueueRepositoryInterface;
use Module\Support\MailManager\Domain\ValueObject\MailId;
use Module\Support\MailManager\Domain\ValueObject\QueueMailStatusEnum;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class CheckMessageStatus implements UseCaseInterface
{
    public function __construct(
        private readonly QueueRepositoryInterface $queueRepository,
    ) {
    }

    public function execute(string $messageUuid): QueueMessageStatusDto
    {
        $message = $this->queueRepository->find(new MailId($messageUuid));
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