<?php

namespace Support\MailManager\Contracts;

use Support\MailManager\Mail;

interface MailerInterface
{
    public function send(Mail $message): void;
}