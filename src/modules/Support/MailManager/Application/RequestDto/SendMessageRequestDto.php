<?php

declare(strict_types=1);

namespace Module\Support\MailManager\Application\RequestDto;

use Module\Support\MailManager\Application\Dto\MailMessageDto;

final class SendMessageRequestDto
{
    public function __construct(
        public readonly MailMessageDto $message,
        public readonly array $context = [],
        public readonly bool $async = true,
    ) {
    }
}