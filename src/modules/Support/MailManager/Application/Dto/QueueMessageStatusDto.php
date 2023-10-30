<?php

namespace Module\Support\MailManager\Application\Dto;

use Module\Support\MailManager\Domain\ValueObject\QueueMailStatusEnum;

class QueueMessageStatusDto
{
    public readonly string $statusDescription;

    public static function notFound(string $uuid): static
    {
        return new static($uuid, null);
    }

    public static function waiting(string $uuid): static
    {
        return new static($uuid, QueueMailStatusEnum::WAITING->value);
    }

    public static function sent(string $uuid): static
    {
        return new static($uuid, QueueMailStatusEnum::SENT->value);
    }

    public static function failed(string $uuid, string $details): static
    {
        return new static($uuid, QueueMailStatusEnum::FAILED->value, $details);
    }

    private function __construct(
        public readonly string $uuid,
        public readonly ?int $status,
        public readonly ?string $details = null,
    ) {
        $this->statusDescription = $this->status
            ? QueueMailStatusEnum::from($this->status)->description()
            : 'Not found';
    }
}
