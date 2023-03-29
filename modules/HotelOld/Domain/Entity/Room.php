<?php

namespace Module\HotelOld\Domain\Entity;

class Room
{
    public function __construct(
        public readonly int    $id,
        public readonly string $name,
        /** @var PriceRate[] $priceRates */
        public readonly array $priceRates,
        public readonly int $guestsNumber
    ) {}
}
