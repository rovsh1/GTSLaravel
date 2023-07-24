<?php

namespace Module\Support\MailManager\Domain\Entity;

use Module\Support\MailManager\Domain\ValueObject\QueueMailStatusEnum;

class QueueMessage
{
    public function __construct(
        public readonly string $uuid,
        public readonly string $payload,
        public readonly QueueMailStatusEnum $status
    ) {
    }

    public function isWaiting(): bool
    {
        return $this->status === QueueMailStatusEnum::WAITING;
    }

    public function isProcessing(): bool
    {
        return $this->status === QueueMailStatusEnum::PROCESSING;
    }

    public function isCompleted(): bool
    {
        return $this->status === QueueMailStatusEnum::SENT || $this->status === QueueMailStatusEnum::FAILED;
    }

    public function isFailed(): bool
    {
        return $this->status === QueueMailStatusEnum::FAILED;
    }

    public function isSent(): bool
    {
        return $this->status === QueueMailStatusEnum::SENT;
    }
}