<?php

namespace Module\Catalog\Domain\Hotel\Entity;

class PriceRate
{
    public function __construct(
        public readonly int    $id,
        public readonly string $name,
        public readonly string $description
    ) {}
}
