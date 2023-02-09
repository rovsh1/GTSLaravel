<?php

namespace GTS\Integration\Traveline\Domain\Factory;

use Custom\Framework\Foundation\Support\EntityFactory\AbstractEntityFactory;
use GTS\Integration\Traveline\Domain\Entity\Reservation;

class ReservationFactory extends AbstractEntityFactory
{
    public static string $entity = Reservation::class;
}
