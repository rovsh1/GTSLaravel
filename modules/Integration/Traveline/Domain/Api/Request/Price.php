<?php

namespace Module\Integration\Traveline\Domain\Api\Request;

use Module\Integration\Traveline\Domain\Service\HotelRoomCodeGeneratorInterface;
use Module\Integration\Traveline\Domain\ValueObject\HotelRoomCode;

class Price
{
    public function __construct(
        public readonly int   $roomId,
        public readonly int   $guestsNumber,
        public readonly float $price,
    ) {}

    public static function fromArray(array $data): self
    {
        //@todo тут работать не будет
        /** @var HotelRoomCode $roomCode */
        $roomCode = app(HotelRoomCodeGeneratorInterface::class)->parseRoomCode($data['code']);
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
