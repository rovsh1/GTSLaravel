<?php

namespace Sdk\Shared\Contracts\Adapter;

use Sdk\Shared\Dto\Mail\MailMessageDto;

interface MailAdapterInterface
{
    public function send(MailMessageDto $messageDto): void;

    public function sendTo(string $to, string $subject, string $body, array $attachments = []): void;
}