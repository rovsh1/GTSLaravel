<?php

namespace Module\Support\MailManager\Domain\ValueObject;

enum QueueMailStatusEnum: int
{
    case WAITING = 1;
    case PROCESSING = 2;
    case SENT = 3;
    case FAILED = 4;

    public function description(): string
    {
        return match ($this) {
            self::WAITING => 'Waiting',
            self::PROCESSING => 'Processing',
            self::SENT => 'Sent',
            self::FAILED => 'Failed',
        };
    }
}