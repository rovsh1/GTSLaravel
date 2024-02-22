<?php

namespace Pkg\MailManager\Contracts;

use Pkg\MailManager\Mail;
use Pkg\MailManager\ValueObject\MailId;

interface MailManagerInterface
{
    public function sendSync(Mail $mailMessage): void;

    public function sendWaitingMessages(): void;

    public function sendWaitingMessage(Mail $mailMessage): void;

    public function resendMessage(MailId $uuid): void;
}