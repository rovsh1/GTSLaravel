<?php

namespace Module\Support\MailManager\Infrastructure\Queue;

use Illuminate\Contracts\Queue\Job as JobContract;
use Module\Support\MailManager\Domain\Entity\Mail;
use Module\Support\MailManager\Domain\Service\MailManagerInterface;
use Module\Support\MailManager\Domain\ValueObject\QueueMailStatusEnum;

class MailJob implements JobContract
{
    public function __construct(
        private readonly MailManagerInterface $mailManager,
        private readonly Mail $queueMessage
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
        return 'SendMail [' . $this->queueMessage->id() . ']';
    }

    public function uuid()
    {
        return $this->queueMessage->id()->value();
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
        return $this->queueMessage->status() !== QueueMailStatusEnum::WAITING;
    }

    public function payload()
    {
        return $this->queueMessage->serialize();
    }

    public function isReleased(): bool
    {
        return $this->queueMessage->status() === QueueMailStatusEnum::WAITING;
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
        return $this->queueMessage->status() === QueueMailStatusEnum::FAILED;
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
