<?php

declare(strict_types=1);

namespace Sdk\Shared\Dto\Mail;

final class SendMessageRequestDto
{
    public function __construct(
        public readonly MailMessageDto $message,
        public readonly array $context = [],
        public readonly bool $async = true,
    ) {
    }
}