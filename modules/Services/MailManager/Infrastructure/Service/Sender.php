<?php

namespace Module\Services\MailManager\Infrastructure\Service;

use Module\Services\MailManager\Domain\Mail\MailInterface;
use Module\Services\MailManager\Domain\Service\LoggerInterface;
use Module\Services\MailManager\Domain\Service\SenderInterface;

class Sender implements SenderInterface
{
    public function __construct(private readonly LoggerInterface $logger) {}

    public function send(MailInterface $mail): void
    {
//        $this->logger->log($mail);
    }
}