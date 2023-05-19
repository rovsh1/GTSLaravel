<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Domain\Entity\Details;

use Module\Booking\Hotel\Domain\ValueObject\Details\GuestCollection;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\SerializableDataInterface;

final class Room implements EntityInterface, SerializableDataInterface
{
    public function __construct(
        private int $id,
        private int $rateId,
        private GuestCollection $guests,
        private string|null $guestNote = null,
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

    public function guestNote(): ?string
    {
        return $this->guestNote;
    }

    public function toData(): array
    {
        return [
            'id' => $this->id,
            'rateId' => $this->rateId,
            'guests' => $this->guests->toData(),
            'guestNote' => $this->guestNote,
        ];
    }

    public static function fromData(array $data): static
    {
        return new static(
            $data['id'],
            $data['rateId'],
            GuestCollection::fromData($data['guests']),
            $data['guestNote'] ?? null,
        );
    }
}
