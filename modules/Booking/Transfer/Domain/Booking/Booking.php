<?php

declare(strict_types=1);

namespace Module\Booking\Transfer\Domain\Booking;

use Carbon\CarbonImmutable;
use Module\Booking\Common\Domain\Entity\AbstractBooking;
use Module\Booking\Common\Domain\ValueObject\BookingId;
use Module\Booking\Common\Domain\ValueObject\BookingPrice;
use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;
use Module\Booking\Common\Domain\ValueObject\BookingTypeEnum;
use Module\Booking\Common\Domain\ValueObject\OrderId;
use Module\Booking\Transfer\Domain\Booking\ValueObject\ServiceInfo;
use Module\Booking\Transfer\Domain\Booking\ValueObject\TransportInfo;
use Module\Shared\Domain\ValueObject\Date;
use Module\Shared\Domain\ValueObject\Id;

class Booking extends AbstractBooking
{
    public function __construct(
        BookingId $id,
        OrderId $orderId,
        BookingStatusEnum $status,
        CarbonImmutable $createdAt,
        Id $creatorId,
        BookingPrice $price,
        private readonly ServiceInfo $serviceInfo,
        private readonly TransportInfo $transportInfo,
        private readonly Date $date,
        private ?string $note
    ) {
        parent::__construct($id, $orderId, $status, $createdAt, $creatorId, $price);
    }

    public function serviceInfo(): ServiceInfo
    {
        return $this->serviceInfo;
    }

    public function transportInfo(): TransportInfo
    {
        return $this->transportInfo;
    }

    public function date(): Date
    {
        return $this->date;
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