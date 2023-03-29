<?php

namespace Module\HotelOld\Domain\Factory;

use Custom\Framework\Foundation\Support\EntityFactory\AbstractEntityFactory;
use Module\HotelOld\Domain\Entity\PriceRate;

class PriceRateFactory extends AbstractEntityFactory
{
    protected string $entity = PriceRate::class;
}
