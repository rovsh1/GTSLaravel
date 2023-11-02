<?php

namespace Module\Generic\Notification\Application\Dto;

final class MailRecipientDto
{
    public function __construct(
        public readonly int $type,
        public readonly array $payload,
    ) {
    }
}