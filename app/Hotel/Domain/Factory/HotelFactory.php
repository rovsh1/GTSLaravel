<?php

namespace GTS\Hotel\Domain\Factory;

use Custom\Framework\Foundation\Support\EntityFactory\AbstractEntityFactory;

use GTS\Hotel\Domain\Entity\Hotel;

class HotelFactory extends AbstractEntityFactory
{
    public static string $entity = Hotel::class;

    public function __construct(
        public readonly int    $id,
        public readonly string $name
    ) {}
}
