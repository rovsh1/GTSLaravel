<?php

namespace GTS\Reservation\HotelReservation\Domain\ValueObject;

class ReservationDetails
{
    public function __construct(
        private ?string $notes,
    ) {}

    public function notes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): void
    {
        $this->notes = $notes;
    }
}
