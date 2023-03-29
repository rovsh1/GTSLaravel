<?php

namespace Module\HotelOld\Domain\Factory;

use Custom\Framework\Foundation\Support\EntityFactory\AbstractEntityFactory;
use Module\HotelOld\Domain\Entity\Hotel;

class HotelFactory extends AbstractEntityFactory
{
    protected string $entity = Hotel::class;
}
