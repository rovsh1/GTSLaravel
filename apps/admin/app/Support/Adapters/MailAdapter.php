<?php

namespace App\Admin\Support\Adapters;

use Illuminate\Database\Eloquent\Builder;
use Module\Support\MailManager\Application\Dto\MailMessageDto;
use Module\Support\MailManager\Application\RequestDto\SendMessageRequestDto;
use Module\Support\MailManager\Application\UseCase\Admin\GetMessagesQueue;
use Module\Support\MailManager\Application\UseCase\Admin\GetTemplatesList;
use Module\Support\MailManager\Application\UseCase\SendMessage;

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

    public function getQueue(array $criteria = []): Builder
    {
        return app(GetMessagesQueue::class)->execute();
    }

    public function getTemplatesList(): array
    {
        return app(GetTemplatesList::class)->execute();
    }
}