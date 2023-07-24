<?php

namespace Module\Support\MailManager\Domain\Repository;

use Module\Support\MailManager\Domain\Entity\QueueMessage;
use Module\Support\MailManager\Domain\ValueObject\QueueMailStatusEnum;

interface QueueRepositoryInterface
{
    public function find(string $uuid): ?QueueMessage;

    public function push(
        string $subject,
        string $payload,
        int $priority = 0,
        QueueMailStatusEnum $status = QueueMailStatusEnum::WAITING,
        array $context = null
    ): QueueMessage;

    public function retry(string $uuid): void;

    public function retryAll(): void;

    public function updateStatus(string $uuid, QueueMailStatusEnum $status): void;

    public function findWaiting(): ?QueueMessage;

    public function clearExpired(\DateTime $date): void;

    public function waitingCount(): int;
}