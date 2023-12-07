<?php

declare(strict_types=1);

namespace Sdk\Booking\ValueObject;

use Sdk\Booking\Enum\StatusEnum;

class BookingStatus
{
    private readonly StatusEnum $status;
    private readonly ?string $reason;

    private function __construct(StatusEnum $status, ?string $reason)
    {
        $this->validate($status, $reason);
    }

    public static function createFromEnum(StatusEnum $status)
    {
        return new static($status, null);
    }

    public static function createNotConfirmed(string $reason)
    {
        return new static(StatusEnum::NOT_CONFIRMED, $reason);
    }

    private function validate(StatusEnum $status, ?string $reason): void
    {
        //@todo проверка на причину в статусах
    }
}
