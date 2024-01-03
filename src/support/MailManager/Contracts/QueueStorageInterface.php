<?php

namespace Support\MailManager\Contracts;

use Support\MailManager\Mail;
use Support\MailManager\ValueObject\MailId;

interface QueueStorageInterface
{
    public function find(MailId $uuid): ?Mail;

    public function push(Mail $mail, int $priority = 0): void;

    public function retry(MailId $uuid): void;

    public function retryAll(): void;

    public function store(Mail $mail): void;

    public function findWaiting(): ?Mail;

    public function clearExpired(\DateTimeInterface $date): void;

    public function waitingCount(): int;
}