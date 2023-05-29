<?php

namespace Module\Hotel\Domain\Factory;

use Module\Hotel\Domain\Entity\Hotel;
use Sdk\Module\Foundation\Support\EntityFactory\AbstractEntityFactory;

class HotelFactory extends AbstractEntityFactory
{
    protected string $entity = Hotel::class;
}
