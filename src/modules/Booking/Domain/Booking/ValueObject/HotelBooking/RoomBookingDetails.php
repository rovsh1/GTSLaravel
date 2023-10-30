<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Booking\ValueObject\HotelBooking;

use Module\Shared\Contracts\CanEquate;
use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Contracts\Support\SerializableDataInterface;
use Module\Shared\ValueObject\Percent;

class RoomBookingDetails implements ValueObjectInterface, SerializableDataInterface, CanEquate
{
    public function __construct(
        private readonly int $rateId,
        private readonly bool $isResident,
        private readonly Condition|null $earlyCheckIn = null,
        private readonly Condition|null $lateCheckOut = null,
        private readonly string|null $guestNote = null,
        private readonly Percent $discount = new Percent(0),
    ) {}

    public function rateId(): int
    {
        return $this->rateId;
    }

    public function lateCheckOut(): ?Condition
    {
        return $this->lateCheckOut;
    }

    public function earlyCheckIn(): ?Condition
    {
        return $this->earlyCheckIn;
    }

    public function guestNote(): ?string
    {
        return $this->guestNote;
    }

    public function isResident(): bool
    {
        return $this->isResident;
    }

    public function discount(): Percent
    {
        return $this->discount;
    }

    public function toData(): array
    {
        return [
            'rateId' => $this->rateId,
            'lateCheckOut' => $this->lateCheckOut?->toData(),
            'earlyCheckIn' => $this->earlyCheckIn?->toData(),
            'guestNote' => $this->guestNote,
            'isResident' => $this->isResident,
            'discount' => $this->discount->value()
        ];
    }

    public static function fromData(array $data): static
    {
        $earlyCheckIn = $data['earlyCheckIn'] ?? null;
        $lateCheckOut = $data['lateCheckOut'] ?? null;

        return new static(
            $data['rateId'],
            $data['isResident'],
            $earlyCheckIn !== null ? Condition::fromData($data['earlyCheckIn']) : null,
            $lateCheckOut !== null ? Condition::fromData($data['lateCheckOut']) : null,
            $data['guestNote'],
            new Percent($data['discount'])
        );
    }

    public function isEqual(mixed $b): bool
    {
        if ($b instanceof RoomBookingDetails) {
            $isEarlyConditionsEqual = $this->earlyCheckIn === $b->earlyCheckIn;
            if ($this->earlyCheckIn !== null) {
                $isEarlyConditionsEqual = $this->earlyCheckIn->isEqual($b->earlyCheckIn);
            }
            $isLateConditionsEqual = $this->lateCheckOut === $b->lateCheckOut;
            if ($this->lateCheckOut !== null) {
                $isLateConditionsEqual = $this->lateCheckOut->isEqual($b->lateCheckOut);
            }
            $isDiscountEqual = $this->discount === $b->discount;
            if ($this->discount !== null) {
                $isDiscountEqual = $this->discount->isEqual($b->discount);
            }

            return $this->rateId === $b->rateId
                && $this->isResident === $b->isResident
                && $isEarlyConditionsEqual
                && $isLateConditionsEqual
                && $this->guestNote === $b->guestNote
                && $isDiscountEqual;
        }

        return $this === $b;
    }
}
