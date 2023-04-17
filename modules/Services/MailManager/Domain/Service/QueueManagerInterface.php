<?php

namespace Module\Services\MailManager\Domain\Service;

use Module\Services\MailManager\Domain\Entity\Mail;
use Module\Services\MailManager\Domain\Entity\QueueMessage;

interface QueueManagerInterface
{
    public function push(Mail $mailMessage, int $priority = 0, array $context = null): QueueMessage;

    public function sendSync(Mail $mailMessage, array $context = null): QueueMessage;

    public function sendWaitingMessages(): void;
}