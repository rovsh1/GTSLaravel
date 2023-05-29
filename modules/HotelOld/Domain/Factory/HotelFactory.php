<?php

namespace Module\HotelOld\Domain\Factory;

use Module\HotelOld\Domain\Entity\Hotel;
use Sdk\Module\Foundation\Support\EntityFactory\AbstractEntityFactory;

class HotelFactory extends AbstractEntityFactory
{
    protected string $entity = Hotel::class;
}
