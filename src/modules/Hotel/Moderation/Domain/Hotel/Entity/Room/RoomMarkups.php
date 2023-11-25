<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Domain\Hotel\Entity\Room;

use Module\Hotel\Moderation\Domain\Hotel\ValueObject\RoomId;
use Module\Shared\Contracts\Domain\EntityInterface;
use Module\Shared\Contracts\Support\SerializableInterface;
use Sdk\Shared\ValueObject\Percent;

final class RoomMarkups implements EntityInterface, SerializableInterface
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

    public function serialize(): array
    {
        return [
            'id' => $this->roomId->value(),
            'discount' => $this->discount->value(),
        ];
    }

    public static function deserialize(array $payload): static
    {
        return new static(
            roomId: new RoomId($payload['id']),
            discount: new Percent($payload['discount']),
        );
    }
}
