<?php

namespace Sdk\Shared\Contracts\Adapter;

use Sdk\Shared\Dto\Mail\SendMessageRequestDto;

interface MailAdapterInterface
{
    public function send(SendMessageRequestDto $requestDto): void;

    public function sendTo(string $to, string $subject, string $body): void;
}