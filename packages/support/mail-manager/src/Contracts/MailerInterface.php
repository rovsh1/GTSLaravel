<?php

namespace Pkg\MailManager\Contracts;

use Pkg\MailManager\Mail;

interface MailerInterface
{
    public function send(Mail $message): void;
}