<?php

namespace Module\Catalog\Domain\Hotel\Factory;

use Module\Catalog\Domain\Hotel\Entity\PriceRate;
use Sdk\Module\Foundation\Support\EntityFactory\AbstractEntityFactory;

class PriceRateFactory extends AbstractEntityFactory
{
    protected string $entity = PriceRate::class;
}
