<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Domain\Entity;

use Carbon\CarbonImmutable;
use Module\Booking\Airport\Domain\ValueObject\Details\AdditionalInfo;
use Module\Booking\Airport\Domain\ValueObject\Details\AirportInfo;
use Module\Booking\Airport\Domain\ValueObject\Details\ServiceInfo;
use Module\Booking\Common\Domain\Entity\AbstractBooking;
use Module\Booking\Common\Domain\ValueObject\BookingId;
use Module\Booking\Common\Domain\ValueObject\BookingPrice;
use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;
use Module\Booking\Common\Domain\ValueObject\BookingTypeEnum;
use Module\Booking\Common\Domain\ValueObject\CancelConditions;
use Module\Booking\Common\Domain\ValueObject\CreatorId;
use Module\Booking\Common\Domain\ValueObject\OrderId;
use Module\Booking\Order\Domain\ValueObject\GuestIdsCollection;

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
        private readonly AirportInfo $airportInfo,
        private CarbonImmutable $date,
        private ?CancelConditions $cancelConditions,
        private AdditionalInfo $additionalInfo,
        private readonly GuestIdsCollection $guestIds,
        private ?string $note
    ) {
        parent::__construct($id, $orderId, $status, $createdAt, $creatorId, $price);
    }

    public function serviceInfo(): ServiceInfo
    {
        return $this->serviceInfo;
    }

    public function airportInfo(): AirportInfo
    {
        return $this->airportInfo;
    }

    public function date(): CarbonImmutable
    {
        return $this->date;
    }

    public function setDate(CarbonImmutable $date): void
    {
        $this->date = $date;
    }

    public function note(): ?string
    {
        return $this->note;
    }

    public function setNote(string|null $note): void
    {
        $this->note = $note;
    }

    public function additionalInfo(): AdditionalInfo
    {
        return $this->additionalInfo;
    }

    public function setAdditionalInfo(AdditionalInfo $additionalInfo): void
    {
        $this->additionalInfo = $additionalInfo;
    }

    public function type(): BookingTypeEnum
    {
        return BookingTypeEnum::AIRPORT;
    }

    public function guestIds(): GuestIdsCollection
    {
        return $this->guestIds;
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
