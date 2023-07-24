<?php

namespace Module\Support\MailManager\Domain\Service;

use Module\Support\MailManager\Domain\Entity\Mail;
use Module\Support\MailManager\Domain\Entity\QueueMessage;

interface QueueManagerInterface
{
    public function size(): int;

    public function pop(): ?QueueMessage;

    public function push(Mail $mailMessage, int $priority = 0, array $context = null): QueueMessage;

    public function sendSync(Mail $mailMessage, array $context = null): QueueMessage;

    public function sendWaitingMessages(): void;

    public function sendWaitingMessage(QueueMessage $queueMessage): void;
}