<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Entity;

use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;
use Module\Booking\Common\Domain\ValueObject\BookingTypeEnum;
use Module\Shared\Domain\Entity\EntityInterface;

abstract class Booking implements EntityInterface, ReservationInterface
{
    public function __construct(
        private readonly int $id,
        private readonly BookingStatusEnum $status,
        private readonly BookingTypeEnum $type,
        private ?string $note = null
    ) {}

    public function id(): int
    {
        return $this->id;
    }

    public function status(): BookingStatusEnum
    {
        return $this->status;
    }

    public function type(): BookingTypeEnum
    {
        return $this->type;
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
