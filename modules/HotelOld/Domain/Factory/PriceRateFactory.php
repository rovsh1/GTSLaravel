<?php

namespace Module\HotelOld\Domain\Factory;

use Module\HotelOld\Domain\Entity\PriceRate;
use Sdk\Module\Foundation\Support\EntityFactory\AbstractEntityFactory;

class PriceRateFactory extends AbstractEntityFactory
{
    protected string $entity = PriceRate::class;
}
