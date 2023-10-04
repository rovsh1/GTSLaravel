<?php

declare(strict_types=1);

namespace Module\Booking\Domain\TransferBooking;

use Carbon\CarbonImmutable;
use Module\Booking\Domain\Shared\Entity\AbstractBooking;
use Module\Booking\Domain\Shared\ValueObject\BookingId;
use Module\Booking\Domain\Shared\ValueObject\BookingPrice;
use Module\Booking\Domain\Shared\ValueObject\BookingStatusEnum;
use Module\Booking\Domain\Shared\ValueObject\BookingTypeEnum;
use Module\Booking\Domain\Shared\ValueObject\CancelConditions;
use Module\Booking\Domain\Shared\ValueObject\CreatorId;
use Module\Booking\Domain\Shared\ValueObject\OrderId;
use Module\Booking\Domain\TransferBooking\ValueObject\Details\ServiceInfo;

class TransferBooking extends AbstractBooking
{
    public function __construct(
        BookingId $id,
        OrderId $orderId,
        BookingStatusEnum $status,
        CarbonImmutable $createdAt,
        CreatorId $creatorId,
        BookingPrice $price,
        private readonly ServiceInfo $serviceInfo,
        private ?string $note,
        private CancelConditions $cancelConditions,
    ) {
        parent::__construct($id, $orderId, $status, $createdAt, $creatorId, $price);
    }

    public function serviceInfo(): ServiceInfo
    {
        return $this->serviceInfo;
    }

    public function cancelConditions(): CancelConditions
    {
        return $this->cancelConditions;
    }

    public function setCancelConditions(CancelConditions $cancelConditions): void
    {
        $this->cancelConditions = $cancelConditions;
    }

    public function note(): ?string
    {
        return $this->note;
    }

    public function setNote(string|null $note): void
    {
        $this->note = $note;
    }

    public function type(): BookingTypeEnum
    {
        return BookingTypeEnum::TRANSFER;
    }
}
