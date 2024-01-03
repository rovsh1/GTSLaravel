<?php

namespace Support\MailManager;

use Sdk\Shared\Contracts\Adapter\MailAdapterInterface;
use Sdk\Shared\Dto\Mail\AttachmentDto;
use Sdk\Shared\Dto\Mail\MailMessageDto;
use Support\MailManager\UseCase\SendMessage;

class MailAdapter implements MailAdapterInterface
{
    public function send(MailMessageDto $messageDto): void
    {
        app(SendMessage::class)->execute($messageDto);
    }

    /**
     * @param string $to
     * @param string $subject
     * @param string $body
     * @param AttachmentDto[] $attachments
     * @return void
     */
    public function sendTo(string $to, string $subject, string $body, array $attachments = []): void
    {
        app(SendMessage::class)->execute(
            new MailMessageDto(
                to: [$to],
                subject: $subject,
                body: $body,
                attachments: $attachments
            )
        );
    }
}
