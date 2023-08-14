<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Entity;

use Carbon\CarbonImmutable;
use Module\Booking\Common\Domain\ValueObject\BookingId;
use Module\Booking\Common\Domain\ValueObject\VoucherId;
use Module\Shared\Domain\Entity\EntityInterface;
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
