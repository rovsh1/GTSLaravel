<?php

namespace Module\Services\MailManager\Application\Command;

use Custom\Framework\Contracts\Bus\CommandHandlerInterface;
use Custom\Framework\Contracts\Bus\CommandInterface;
use Module\Services\MailManager\Application\Dto\QueueMessageStatusDto;
use Module\Services\MailManager\Domain\Repository\QueueRepositoryInterface;

class CheckMessageStatusHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly QueueRepositoryInterface $queueRepository,
    ) {
    }

    public function handle(CommandInterface|CheckMessageStatus $command): QueueMessageStatusDto
    {
        $queueRecord = $this->queueRepository->find($command->uuid);
        if (!$queueRecord) {
            return QueueMessageStatusDto::notFound($command->uuid);
        }

        if ($queueRecord->isFailed()) {
            return QueueMessageStatusDto::failed($command->uuid, $queueRecord->exception ?? '');
        } elseif ($queueRecord->isSent()) {
            return QueueMessageStatusDto::sent($command->uuid);
        } else {
            return QueueMessageStatusDto::waiting($command->uuid);
        }
    }
}
