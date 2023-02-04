<?php

namespace GTS\Reservation\Application\Query;

use GTS\Reservation\Domain\ValueObject\ReservationTypeEnum;
use GTS\Shared\Application\Query\QueryInterface;

class FindReservationByType implements QueryInterface
{
    public function __construct(
        public readonly int $id,
        public readonly ReservationTypeEnum $type
    ) {}
}
