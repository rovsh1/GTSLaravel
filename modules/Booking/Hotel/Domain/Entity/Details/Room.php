<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Domain\Entity\Details;

use Module\Booking\Hotel\Domain\ValueObject\Details\Condition;
use Module\Booking\Hotel\Domain\ValueObject\Details\Guest;
use Module\Booking\Hotel\Domain\ValueObject\Details\GuestCollection;
use Module\Booking\Hotel\Domain\ValueObject\Details\RoomStatusEnum;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\Percent;
use Module\Shared\Domain\ValueObject\SerializableDataInterface;

final class Room implements EntityInterface, SerializableDataInterface
{
    public function __construct(
        private int $id,
        private int $rateId,
        private RoomStatusEnum $status,
        private GuestCollection $guests,
        private bool $isResident,
        private int $roomCount = 1,
        private Condition|null $earlyCheckIn = null,
        private Condition|null $lateCheckOut = null,
        private string|null $guestNote = null,
        private Percent $discount = new Percent(0),
    ) {}

    public function id(): int
    {
        return $this->id;
    }

    public function guests(): GuestCollection
    {
        return $this->guests;
    }

    public function rateId(): int
    {
        return $this->rateId;
    }

    public function status(): RoomStatusEnum
    {
        return $this->status;
    }

    public function roomCount(): int
    {
        return $this->roomCount;
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

    public function addGuest(Guest $guest): void
    {
        $this->guests->add($guest);
    }

    public function updateGuest(int $index, Guest $guest): void
    {
        $this->guests->offsetSet($index, $guest);
    }

    public function toData(): array
    {
        return [
            'id' => $this->id,
            'rateId' => $this->rateId,
            'status' => $this->status->value,
            'guests' => $this->guests->toData(),
            'guestNote' => $this->guestNote,
            'isResident' => $this->isResident,
            'discount' => $this->discount->value(),
            'roomCount' => $this->roomCount,
            'earlyCheckIn' => $this->earlyCheckIn?->toData(),
            'lateCheckOut' => $this->lateCheckOut?->toData(),
        ];
    }

    public static function fromData(array $data): static
    {
        $earlyCheckIn = $data['earlyCheckIn'] ?? null;
        $lateCheckOut = $data['lateCheckOut'] ?? null;
        return new static(
            id: $data['id'],
            rateId: $data['rateId'],
            status: RoomStatusEnum::from($data['status']),
            guests: GuestCollection::fromData($data['guests']),
            isResident: $data['isResident'],
            roomCount: $data['roomCount'],
            earlyCheckIn: $earlyCheckIn !== null ? Condition::fromData($data['earlyCheckIn']) : null,
            lateCheckOut: $lateCheckOut !== null ? Condition::fromData($data['lateCheckOut']) : null,
            guestNote: $data['guestNote'] ?? null,
            discount: new Percent($data['discount'])
        );
    }
}
