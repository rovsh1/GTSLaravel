<?php

declare(strict_types=1);

namespace Sdk\Booking\ValueObject\HotelBooking;

use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Sdk\Shared\Contracts\Support\CanEquate;
use Sdk\Shared\Contracts\Support\SerializableInterface;
use Sdk\Shared\ValueObject\Percent;

class AccommodationDetails implements ValueObjectInterface, SerializableInterface, CanEquate
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

    public function serialize(): array
    {
        return [
            'rateId' => $this->rateId,
            'lateCheckOut' => $this->lateCheckOut?->serialize(),
            'earlyCheckIn' => $this->earlyCheckIn?->serialize(),
            'guestNote' => $this->guestNote,
            'isResident' => $this->isResident,
            'discount' => $this->discount->value()
        ];
    }

    public static function deserialize(array $payload): static
    {
        $earlyCheckIn = $payload['earlyCheckIn'] ?? null;
        $lateCheckOut = $payload['lateCheckOut'] ?? null;

        return new static(
            $payload['rateId'],
            $payload['isResident'],
            $earlyCheckIn !== null ? Condition::deserialize($payload['earlyCheckIn']) : null,
            $lateCheckOut !== null ? Condition::deserialize($payload['lateCheckOut']) : null,
            $payload['guestNote'],
            new Percent($payload['discount'])
        );
    }

    public function isEqual(mixed $b): bool
    {
        if ($b instanceof AccommodationDetails) {
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
