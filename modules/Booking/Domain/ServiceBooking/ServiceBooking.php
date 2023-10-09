<?php

declare(strict_types=1);

namespace Module\Booking\Domain\ServiceBooking;

use Carbon\CarbonImmutable;
use Module\Booking\Domain\Order\ValueObject\OrderId;
use Module\Booking\Domain\ServiceBooking\ValueObject\BookingId;
use Module\Booking\Domain\ServiceBooking\ValueObject\BookingPrice;
use Module\Booking\Domain\Shared\Entity\AbstractBooking;
use Module\Booking\Domain\Shared\ValueObject\BookingStatusEnum;
use Module\Booking\Domain\Shared\ValueObject\CancelConditions;
use Module\Booking\Domain\Shared\ValueObject\CreatorId;
use Module\Shared\Enum\ServiceTypeEnum;

class ServiceBooking extends AbstractBooking
{
    public function __construct(
        BookingId $id,
        OrderId $orderId,
        BookingStatusEnum $status,
        CarbonImmutable $createdAt,
        CreatorId $creatorId,
        BookingPrice $price,
        private ?CancelConditions $cancelConditions,
        private ?string $note,
        private readonly ServiceTypeEnum $serviceType,
    ) {
        parent::__construct($id, $orderId, $status, $createdAt, $creatorId, $price);
    }

    public function serviceType(): ServiceTypeEnum
    {
        return $this->serviceType;
    }

    public function note(): ?string
    {
        return $this->note;
    }

    public function setNote(string|null $note): void
    {
        $this->note = $note;
    }

    public function cancelConditions(): ?CancelConditions
    {
        return $this->cancelConditions;
    }

    public function setCancelConditions(?CancelConditions $cancelConditions): void
    {
        $this->cancelConditions = $cancelConditions;
    }
}
