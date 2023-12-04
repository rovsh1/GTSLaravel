<?php

namespace App\Admin\Support\Adapters;

use Module\Support\MailManager\Application\UseCase\SendMessage;
use Sdk\Shared\Dto\Mail\MailMessageDto;
use Sdk\Shared\Dto\Mail\SendMessageRequestDto;

class MailAdapter
{
    public function sendTo(string $to, string $subject, string $body): void
    {
        app(SendMessage::class)->execute(
            new SendMessageRequestDto(
                message: new MailMessageDto(
                    to: [$to],
                    subject: $subject,
                    body: $body
                ),
                context: []
            )
        );
    }
}