<?php

namespace Module\Reservation\HotelReservation\Domain\ValueObject;

use Module\Reservation\Common\Domain\ValueObject\ClientTypeEnum;

class Client extends \Module\Reservation\Common\Domain\ValueObject\Client
{
    public function __construct(
        int                     $id,
        ClientTypeEnum          $type,
        private readonly string $fullName,
    )
    {
        parent::__construct($id, $type);
    }

    public function fullName(): string
    {
        return $this->fullName;
    }
}
