<?php

declare(strict_types=1);

namespace Module\Generic\Notification\Domain\MailSettings\Dto;

final class MailDataDto
{
    public function __construct(
        public readonly ?int $hotelId,
        public readonly ?int $clientId,
    ) {
    }
}