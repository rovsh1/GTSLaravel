<?php

namespace GTS\Hotel\Domain\Factory;

use Custom\Framework\Foundation\Support\EntityFactory\AbstractEntityFactory;

use GTS\Hotel\Domain\Entity\Hotel;

class HotelFactory extends AbstractEntityFactory
{
    protected string $entity = Hotel::class;
}
