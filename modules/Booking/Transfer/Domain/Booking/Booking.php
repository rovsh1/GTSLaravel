<?php

declare(strict_types=1);

namespace Module\Booking\Transfer\Domain\Booking;

use Carbon\CarbonImmutable;
use Module\Booking\Common\Domain\Entity\AbstractBooking;
use Module\Booking\Common\Domain\ValueObject\BookingId;
use Module\Booking\Common\Domain\ValueObject\BookingPrice;
use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;
use Module\Booking\Common\Domain\ValueObject\BookingTypeEnum;
use Module\Booking\Common\Domain\ValueObject\CancelConditions;
use Module\Booking\Common\Domain\ValueObject\CreatorId;
use Module\Booking\Common\Domain\ValueObject\OrderId;
use Module\Booking\Transfer\Domain\Booking\ValueObject\Details\ServiceInfo;

class Booking extends AbstractBooking
{
    public function __construct(
        BookingId $id,
        OrderId $orderId,
        BookingStatusEnum $status,
        CarbonImmutable $createdAt,
        CreatorId $creatorId,
        BookingPrice $price,
        private readonly ServiceInfo $serviceInfo,
        private ?CancelConditions $cancelConditions,
        private ?string $note
    ) {
        parent::__construct($id, $orderId, $status, $createdAt, $creatorId, $price);
    }

    public function serviceInfo(): ServiceInfo
    {
        return $this->serviceInfo;
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

    public function type(): BookingTypeEnum
    {
        return BookingTypeEnum::TRANSFER;
    }
}
