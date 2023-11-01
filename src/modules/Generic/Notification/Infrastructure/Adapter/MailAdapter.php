<?php

namespace Module\Generic\Notification\Infrastructure\Adapter;

use Module\Generic\Notification\Domain\Shared\Adapter\MailAdapterInterface;
use Module\Support\MailManager\Application\RequestDto\SendMessageRequestDto;
use Module\Support\MailManager\Application\UseCase\SendMessage;

class MailAdapter implements MailAdapterInterface
{
    public function send(SendMessageRequestDto $requestDto): void
    {
        app(SendMessage::class)->execute($requestDto);
    }
}