<?php

namespace Module\Support\MailManager\Domain\Service;

use Module\Support\MailManager\Domain\Entity\Mail;
use Module\Support\MailManager\Domain\ValueObject\MailId;

interface QueueManagerInterface
{
    public function size(): int;

    public function pop(): ?Mail;

    public function push(Mail $mailMessage, int $priority = 0, array $context = null): void;

    public function resendMessage(MailId $uuid): void;

    public function sendSync(Mail $mailMessage, array $context = null): void;

    public function sendWaitingMessages(): void;

    public function sendWaitingMessage(Mail $mailMessage): void;
}