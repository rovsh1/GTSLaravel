<?php

declare(strict_types=1);

namespace Module\Booking\Domain\ServiceBooking\ValueObject;

use Module\Shared\Enum\ServiceTypeEnum;

class ServiceInfo
{
    public function __construct(
        private readonly ServiceId $id,
        private readonly string $title,
        private readonly ServiceTypeEnum $type
    ) {}

    public function id(): ServiceId
    {
        return $this->id;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function type(): ServiceTypeEnum
    {
        return $this->type;
    }
}
