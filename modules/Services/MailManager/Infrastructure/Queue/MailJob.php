<?php

namespace Module\Services\MailManager\Infrastructure\Queue;

use Module\Services\MailManager\Domain\Entity\QueueMessage;
use Module\Services\MailManager\Domain\Service\QueueManagerInterface;
use Module\Services\MailManager\Domain\ValueObject\QueueMailStatusEnum;
use Illuminate\Contracts\Queue\Job as JobContract;

class MailJob implements JobContract
{
    public function __construct(
        private readonly QueueManagerInterface $mailManager,
        private readonly QueueMessage $queueMessage
    ) {
    }

    public function release($delay = 0)
    {
    }

    public function attempts(): int
    {
        return 1;
    }

    public function fire(): void
    {
        $this->mailManager->sendWaitingMessage($this->queueMessage);
    }

    public function getName(): string
    {
        return 'SendMail [' . $this->queueMessage->uuid . ']';
    }

    public function uuid()
    {
        return $this->queueMessage->uuid;
    }

    public function getJobId()
    {
        return $this->uuid();
    }

    public function getRawBody()
    {
        return '';
    }

    public function getQueue()
    {
        return 'mail';
    }

    public function isDeleted(): bool
    {
        return $this->queueMessage->status !== QueueMailStatusEnum::WAITING;
    }

    public function payload()
    {
        return null;
    }

    public function isReleased(): bool
    {
        return $this->queueMessage->status === QueueMailStatusEnum::WAITING;
    }

    public function delete()
    {
    }

    public function isDeletedOrReleased(): bool
    {
        return $this->isDeleted() || $this->isReleased();
    }

    public function hasFailed(): bool
    {
        return $this->queueMessage->status === QueueMailStatusEnum::FAILED;
    }

    public function markAsFailed()
    {
    }

    public function fail($e = null)
    {
    }

    public function maxTries()
    {
        return null;
    }

    public function maxExceptions()
    {
        return null;
    }

    public function timeout()
    {
        return null;
    }

    public function retryUntil()
    {
        return null;
    }

    public function resolveName()
    {
        return null;
    }

    public function getConnectionName()
    {
        return null;
    }
}
