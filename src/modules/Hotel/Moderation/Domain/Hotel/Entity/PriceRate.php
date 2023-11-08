<?php

namespace Module\Hotel\Moderation\Domain\Hotel\Entity;

class PriceRate
{
    public function __construct(
        public readonly int    $id,
        public readonly string $name,
        public readonly string $description
    ) {}
}
