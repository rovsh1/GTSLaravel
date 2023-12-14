<?php

namespace Module\Support\MailManager\Domain\Service;

use Module\Support\MailManager\Domain\Entity\Mail;
use Module\Support\MailManager\Domain\ValueObject\MailId;

interface MailManagerInterface
{
    public function sendSync(Mail $mailMessage): void;

    public function sendWaitingMessages(): void;

    public function sendWaitingMessage(Mail $mailMessage): void;

    public function resendMessage(MailId $uuid): void;
}