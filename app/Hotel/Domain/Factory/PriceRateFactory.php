<?php

namespace GTS\Hotel\Domain\Factory;

use Custom\Framework\Foundation\Support\EntityFactory\AbstractEntityFactory;

use GTS\Hotel\Domain\Entity\PriceRate;

class PriceRateFactory extends AbstractEntityFactory
{
    public static string $entity = PriceRate::class;

    public function __construct(
        public readonly int    $id,
        public readonly string $name,
        public readonly string $text
    ) {}
}
