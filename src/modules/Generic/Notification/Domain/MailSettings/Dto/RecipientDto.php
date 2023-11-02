<?php

declare(strict_types=1);

namespace Module\Generic\Notification\Domain\MailSettings\Dto;

final class RecipientDto
{
    public function __construct(
        public readonly string $email,
        public readonly ?string $presentation,
    ) {
    }
}