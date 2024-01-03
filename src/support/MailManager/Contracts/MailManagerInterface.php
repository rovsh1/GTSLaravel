<?php

namespace Support\MailManager\Contracts;

use Support\MailManager\Mail;
use Support\MailManager\ValueObject\MailId;

interface MailManagerInterface
{
    public function sendSync(Mail $mailMessage): void;

    public function sendWaitingMessages(): void;

    public function sendWaitingMessage(Mail $mailMessage): void;

    public function resendMessage(MailId $uuid): void;
}