<?php

namespace Module\Booking\Hotel\Application\Dto;

use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use Module\Booking\Hotel\Domain\Entity\Reservation;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class ReservationDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int             $id,
        public readonly int             $hotelId,
        public readonly CarbonPeriod    $reservationPeriod,
        public readonly ?string         $checkInTime,
        public readonly ?string         $checkOutTime,
        public readonly CarbonInterface $createdDate,
        public readonly ClientDto       $client,
        public readonly int             $statusId,
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|Reservation $reservation): static
    {
        return new static(
            $reservation->id(),
            $reservation->hotel()->id(),
            new CarbonPeriod($reservation->period()->dateFrom(), $reservation->period()->dateTo()),
            $reservation->hotel()->checkInTime(),
            $reservation->hotel()->checkOutTime(),
            $reservation->createdDate(),
            ClientDto::fromDomain($reservation->client()),
            $reservation->status()->id()->value
        );
    }
}
