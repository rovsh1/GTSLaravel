<?php

declare(strict_types=1);

namespace Module\Booking\Domain\ServiceBooking\ValueObject;

class ServiceInfo
{
    public function __construct(
        private readonly ServiceId $id,
        private readonly string $title,
    ) {}

    public function id(): ServiceId
    {
        return $this->id;
    }

    public function title(): string
    {
        return $this->title;
    }
}
