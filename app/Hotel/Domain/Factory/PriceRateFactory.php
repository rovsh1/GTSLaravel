<?php

namespace GTS\Hotel\Domain\Factory;

use Custom\Framework\Foundation\Support\EntityFactory\AbstractEntityFactory;
use GTS\Hotel\Domain\Entity\PriceRate;

class PriceRateFactory extends AbstractEntityFactory
{
    protected string $entity = PriceRate::class;
}
