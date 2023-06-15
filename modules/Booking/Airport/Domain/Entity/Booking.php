<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Domain\Entity;

use Carbon\CarbonImmutable;
use Module\Booking\Airport\Domain\ValueObject\Details\AirportInfo;
use Module\Booking\Common\Domain\Entity\AbstractBooking;
use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;
use Module\Booking\Common\Domain\ValueObject\BookingTypeEnum;
use Module\Shared\Domain\ValueObject\Id;

class Booking extends AbstractBooking
{
    public function __construct(
        Id $id,
        Id $orderId,
        BookingStatusEnum $status,
        BookingTypeEnum $type,
        CarbonImmutable $createdAt,
        Id $creatorId,
        private readonly Id $serviceId,
        private readonly AirportInfo $airportInfo,
        private readonly CarbonImmutable $date,
        private ?string $note
    ) {
        parent::__construct($id, $orderId, $status, $type, $createdAt, $creatorId);
    }

    public function serviceId(): Id
    {
        return $this->serviceId;
    }

    public function airportInfo(): AirportInfo
    {
        return $this->airportInfo;
    }

    public function date(): CarbonImmutable
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
}
