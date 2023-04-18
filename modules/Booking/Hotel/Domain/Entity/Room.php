<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Domain\Entity;

use Module\Booking\Hotel\Domain\Entity\Room\Guest;
use Module\Booking\Hotel\Domain\ValueObject\Price;
use Module\Shared\Domain\Entity\EntityInterface;

class Room implements EntityInterface
{
    public function __construct(
        private int         $id,
        /** @var Guest[] $guests */
        private array       $guests,
        private int         $rateId,
        private Price       $priceNetto,
        private string|null $checkInTime,
        private string|null $checkOutTime,
        private string|null $guestNote = null,
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

    public function guestNote(): ?string
    {
        return $this->guestNote;
    }

    public function priceNetto(): Price
    {
        return $this->priceNetto;
    }
}
