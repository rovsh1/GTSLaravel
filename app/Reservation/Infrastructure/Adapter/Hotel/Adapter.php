<?php

namespace GTS\Reservation\Infrastructure\Adapter\Hotel;

use GTS\Hotel\Infrastructure\Api\Search\Api;

class Adapter implements AdapterInterface
{

    public function __construct(private readonly Api $hotelApi)
    {
    }

    public function findById(int $id)
    {
        $hotelDto = $this->hotelApi->findById($id);

        return $hotelDto;
    }
}
