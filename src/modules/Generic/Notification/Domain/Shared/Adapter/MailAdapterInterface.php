<?php

namespace Module\Generic\Notification\Domain\Shared\Adapter;

use Module\Support\MailManager\Application\RequestDto\SendMessageRequestDto;

interface MailAdapterInterface
{
    public function send(SendMessageRequestDto $requestDto): void;
}