<?php

namespace Module\Shared\Infrastructure\Adapter;

use Module\Support\MailManager\Application\UseCase\SendMessage;
use Sdk\Shared\Contracts\Adapter\MailAdapterInterface;
use Sdk\Shared\Dto\FileDto;
use Sdk\Shared\Dto\Mail\MailMessageDto;

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
     * @param FileDto[] $attachments
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