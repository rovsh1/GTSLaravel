<?php

namespace Module\Support\MailManager\Domain\Service;

use Module\Support\MailManager\Domain\Entity\Mail;

interface MailerInterface
{
    public function send(Mail $message): void;
}