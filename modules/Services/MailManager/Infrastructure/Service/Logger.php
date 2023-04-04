<?php

namespace Module\Services\MailManager\Infrastructure\Service;

use Module\Services\MailManager\Domain\Mail\MailInterface;
use Module\Services\MailManager\Domain\Service\LoggerInterface;

class Logger implements LoggerInterface
{
    public function log(MailInterface $mail): void {}
}