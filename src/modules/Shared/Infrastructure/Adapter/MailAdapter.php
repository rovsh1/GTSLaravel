<?php

namespace Module\Shared\Infrastructure\Adapter;

use Module\Support\MailManager\Application\UseCase\SendMessage;
use Sdk\Shared\Contracts\Adapter\MailAdapterInterface;
use Sdk\Shared\Dto\Mail\SendMessageRequestDto;

class MailAdapter implements MailAdapterInterface
{
    public function send(SendMessageRequestDto $requestDto): void
    {
        app(SendMessage::class)->execute($requestDto);
    }
}