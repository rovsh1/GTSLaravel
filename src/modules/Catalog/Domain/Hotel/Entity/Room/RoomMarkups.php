<?php

declare(strict_types=1);

namespace Module\Catalog\Domain\Hotel\Entity\Room;

use Module\Catalog\Domain\Hotel\ValueObject\RoomId;
use Module\Shared\Contracts\Domain\EntityInterface;
use Module\Shared\Contracts\Support\SerializableDataInterface;
use Module\Shared\ValueObject\Percent;

final class RoomMarkups implements EntityInterface, SerializableDataInterface
{
    public function __construct(
        private readonly RoomId $roomId,
        private Percent $discount,
    ) {}

    public static function buildEmpty(RoomId $id): static
    {
        return new static(
            $id,
            new Percent(0),
        );
    }

    public function id(): RoomId
    {
        return $this->roomId;
    }

    public function roomId(): RoomId
    {
        return $this->roomId;
    }

    public function discount(): Percent
    {
        return $this->discount;
    }

    public function setDiscount(Percent $discount): void
    {
        $this->discount = $discount;
    }

    public function toData(): array
    {
        return [
            'id' => $this->roomId->value(),
            'discount' => $this->discount->value(),
        ];
    }

    public static function fromData(array $data): static
    {
        return new static(
            roomId: new RoomId($data['id']),
            discount: new Percent($data['discount']),
        );
    }
}
