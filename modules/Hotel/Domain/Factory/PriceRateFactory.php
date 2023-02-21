<?php

namespace Module\Hotel\Domain\Factory;

use Custom\Framework\Foundation\Support\EntityFactory\AbstractEntityFactory;
use Module\Hotel\Domain\Entity\PriceRate;

class PriceRateFactory extends AbstractEntityFactory
{
    protected string $entity = PriceRate::class;
}
