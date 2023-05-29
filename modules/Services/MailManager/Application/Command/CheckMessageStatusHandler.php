<?php

namespace Module\Services\MailManager\Application\Command;

use Module\Services\MailManager\Application\Dto\QueueMessageStatusDto;
use Module\Services\MailManager\Domain\Repository\QueueRepositoryInterface;
use Sdk\Module\Contracts\Bus\CommandHandlerInterface;
use Sdk\Module\Contracts\Bus\CommandInterface;

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
