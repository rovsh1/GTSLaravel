<?php

namespace Module\Shared\Infrastructure\Adapter;

use Module\Support\MailManager\Application\UseCase\SendMessage;
use Sdk\Shared\Contracts\Adapter\MailAdapterInterface;
use Sdk\Shared\Dto\Mail\MailMessageDto;
use Sdk\Shared\Dto\Mail\SendMessageRequestDto;

class MailAdapter implements MailAdapterInterface
{
    public function send(SendMessageRequestDto $requestDto): void
    {
        app(SendMessage::class)->execute($requestDto);
    }

    public function sendTo(string $to, string $subject, string $body): void
    {
        app(SendMessage::class)->execute(
            new SendMessageRequestDto(
                message: new MailMessageDto(
                    to: [$to],
                    subject: $subject,
                    body: $body
                )
            )
        );
    }
}