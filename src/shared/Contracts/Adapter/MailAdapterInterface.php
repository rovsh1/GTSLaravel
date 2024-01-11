<?php

namespace Shared\Contracts\Adapter;

use Sdk\Shared\Dto\Mail\AttachmentDto;
use Sdk\Shared\Dto\Mail\MailMessageDto;

interface MailAdapterInterface
{
    public function send(MailMessageDto $messageDto): void;

    /**
     * @param string $to
     * @param string $subject
     * @param string $body
     * @param AttachmentDto[] $attachments
     * @return void
     */
    public function sendTo(string $to, string $subject, string $body, array $attachments = []): void;
}
