<?php

namespace Module\Hotel\Domain\Factory;

use Custom\Framework\Foundation\Support\EntityFactory\AbstractEntityFactory;
use Module\Hotel\Domain\Entity\Hotel;

class HotelFactory extends AbstractEntityFactory
{
    protected string $entity = Hotel::class;
}
