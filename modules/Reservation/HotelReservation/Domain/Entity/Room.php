<?php

declare(strict_types=1);

namespace Module\Reservation\HotelReservation\Domain\Entity;

use Module\Reservation\HotelReservation\Domain\Entity\Room\Guest;
use Module\Shared\Domain\Entity\EntityInterface;

class Room implements EntityInterface
{
    public function __construct(
        private int     $id,
        /** @var Guest[] $guests */
        private array   $guests,
        private int     $rateId,
        private ?string $checkInTime,
        private ?string $checkOutTime,
    ) {}

    /**
     * @return int
     */
    public function id(): int
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function guests(): array
    {
        return $this->guests;
    }

    /**
     * @return int
     */
    public function rateId(): int
    {
        return $this->rateId;
    }

    /**
     * @return string|null
     */
    public function checkInTime(): ?string
    {
        return $this->checkInTime;
    }

    /**
     * @return string|null
     */
    public function checkOutTime(): ?string
    {
        return $this->checkOutTime;
    }
}
