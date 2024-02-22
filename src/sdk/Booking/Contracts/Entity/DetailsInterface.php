<?php

namespace Sdk\Booking\Contracts\Entity;

use DateTimeInterface;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\DetailsId;
use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Module\Support\DateTimeImmutable;
use Sdk\Shared\Enum\ServiceTypeEnum;

interface DetailsInterface extends BookingPartInterface
{
    public function id(): DetailsId;

    public function bookingId(): BookingId;

    public function serviceType(): ServiceTypeEnum;

    public function serviceDate(): ?DateTimeImmutable;

    /**
     * @return DomainEventInterface[]
     */
    public function pullEvents(): array;
}
