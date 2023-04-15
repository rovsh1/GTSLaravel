<?php

namespace Module\Services\MailManager\Domain\Service;

use Module\Services\MailManager\Domain\Entity\Mail;

interface MailerInterface
{
    public function send(Mail $message): void;
}