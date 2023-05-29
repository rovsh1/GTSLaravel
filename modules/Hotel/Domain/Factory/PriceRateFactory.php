<?php

namespace Module\Hotel\Domain\Factory;

use Module\Hotel\Domain\Entity\PriceRate;
use Sdk\Module\Foundation\Support\EntityFactory\AbstractEntityFactory;

class PriceRateFactory extends AbstractEntityFactory
{
    protected string $entity = PriceRate::class;
}
