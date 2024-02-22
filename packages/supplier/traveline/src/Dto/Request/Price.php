<?php

namespace Pkg\Supplier\Traveline\Dto\Request;

use Pkg\Supplier\Traveline\Dto\HotelRoomCodeDto;

class Price
{
    public function __construct(
        public readonly int $roomId,
        public readonly int $guestsCount,
        public readonly float $price,
    ) {}

    public static function fromArray(array $data): self
    {
        $roomCode = HotelRoomCodeDto::fromString($data['code']);

        return new self(
            $roomCode->roomId,
            $roomCode->personQuantity,
            $data['price'],
        );
    }

    /**
     * @param array $items
     * @return self[]
     */
    public static function collectionFromArray(array $items): array
    {
        return array_map(fn(array $data) => static::fromArray($data), $items);
    }
}
