<?php

namespace GTS\Hotel\Domain\Entity;

class Room
{
    public function __construct(
        public readonly int    $id,
        public readonly string $name,
        public readonly ?PriceRate $priceRate
    ) {}
}
