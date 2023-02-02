<?php

namespace GTS\Hotel\Domain\Factory;

use GTS\Hotel\Domain\Entity\Hotel;
use GTS\Shared\Domain\Factory\AbstractEntityFactory;

class HotelFactory extends AbstractEntityFactory
{
    public static string $entity = Hotel::class;

    public function __construct(
        public readonly int    $id,
        public readonly string $name
    ) {}
}
