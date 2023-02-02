<?php

namespace GTS\Hotel\Domain\Factory;

use GTS\Hotel\Domain\Entity\PriceRate;
use GTS\Shared\Domain\Factory\AbstractEntityFactory;

class PriceRateFactory extends AbstractEntityFactory
{
    public static string $entity = PriceRate::class;

    public function __construct(
        public readonly int    $id,
        public readonly string $name,
        public readonly string $text
    ) {}
}
