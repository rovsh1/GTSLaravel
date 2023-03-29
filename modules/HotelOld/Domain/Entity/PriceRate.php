<?php

namespace Module\HotelOld\Domain\Entity;

class PriceRate
{
    public function __construct(
        public readonly int    $id,
        public readonly string $name,
        public readonly string $text
    ) {}
}
