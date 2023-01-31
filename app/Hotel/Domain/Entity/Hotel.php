<?php

namespace GTS\Hotel\Domain\Entity;

class Hotel
{
    public function __construct(
        public readonly int    $id,
        public readonly string $name
    ) {}
}
