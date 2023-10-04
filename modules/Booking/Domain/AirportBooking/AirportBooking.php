<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Domain\Booking;

use Carbon\CarbonImmutable;
use Module\Booking\Airport\Domain\Booking\ValueObject\Details\AdditionalInfo;
use Module\Booking\Airport\Domain\Booking\ValueObject\Details\AirportInfo;
use Module\Booking\Airport\Domain\Booking\ValueObject\Details\ServiceInfo;
use Module\Booking\Domain\Order\ValueObject\GuestIdsCollection;
use Module\Booking\Domain\Shared\Entity\AbstractBooking;
use Module\Booking\Domain\Shared\ValueObject\BookingId;
use Module\Booking\Domain\Shared\ValueObject\BookingPrice;
use Module\Booking\Domain\Shared\ValueObject\BookingStatusEnum;
use Module\Booking\Domain\Shared\ValueObject\BookingTypeEnum;
use Module\Booking\Domain\Shared\ValueObject\CancelConditions;
use Module\Booking\Domain\Shared\ValueObject\CreatorId;
use Module\Booking\Domain\Shared\ValueObject\OrderId;

class AirportBooking extends AbstractBooking
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
