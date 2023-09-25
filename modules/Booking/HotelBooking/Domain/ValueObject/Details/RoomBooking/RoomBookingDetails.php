<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking;

use Module\Booking\HotelBooking\Domain\ValueObject\Details\Condition;
use Module\Shared\Contracts\CanEquate;
use Module\Shared\Domain\ValueObject\Percent;
use Module\Shared\Domain\ValueObject\SerializableDataInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class RoomBookingDetails implements ValueObjectInterface, SerializableDataInterface, CanEquate
{
    public function __construct(
        private int $rateId,
        private bool $isResident,
        private Condition|null $earlyCheckIn = null,
        private Condition|null $lateCheckOut = null,
        private string|null $guestNote = null,
        private Percent $discount = new Percent(0),
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
            $isEarlyConditionsEqual = $this->earlyCheckIn === null;
            if (!$isEarlyConditionsEqual) {
                return $this->earlyCheckIn->isEqual($b->earlyCheckIn);
            }
            $isLateConditionsEqual = $this->lateCheckOut === null;
            if (!$isLateConditionsEqual) {
                return $this->lateCheckOut->isEqual($b->lateCheckOut);
            }
            $isDiscountEqual = $this->discount === null;
            if (!$isDiscountEqual) {
                return $this->discount->isEqual($b->discount);
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
