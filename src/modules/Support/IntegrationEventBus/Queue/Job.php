<?php

namespace Module\Support\IntegrationEventBus\Queue;

use Illuminate\Contracts\Queue\Job as JobContract;
use Module\Support\IntegrationEventBus\Entity\Message;
use Module\Support\IntegrationEventBus\Service\MessageSender;

class Job implements JobContract
{
    public function __construct(
        private readonly MessageSender $messageSender,
        private readonly Message $message
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
        $this->messageSender->send($this->message);
    }

    public function getName(): string
    {
        return 'IntegrationEvent [' . $this->message->uuid() . ']';
    }

    public function uuid()
    {
        return $this->message->uuid();
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
        return $this->message->isDeleted();
    }

    public function payload()
    {
        return $this->message->serialize();
    }

    public function isReleased(): bool
    {
        return $this->message->isReleased();
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
        return $this->message->isFailed();
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
