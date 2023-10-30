<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Shared\Entity;

use Carbon\CarbonImmutable;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Shared\ValueObject\VoucherId;
use Module\Shared\Contracts\Domain\EntityInterface;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;

class Voucher extends AbstractAggregateRoot implements EntityInterface
{
    public function __construct(
        private readonly VoucherId $id,
        private readonly BookingId $bookingId,
        private readonly CarbonImmutable $dateCreate,
    ) {}

    public function id(): VoucherId
    {
        return $this->id;
    }

    public function bookingId(): BookingId
    {
        return $this->bookingId;
    }

    public function dateCreate(): CarbonImmutable
    {
        return $this->dateCreate;
    }
}
