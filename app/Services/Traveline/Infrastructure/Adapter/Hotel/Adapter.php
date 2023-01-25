<?php

namespace GTS\Services\Traveline\Infrastructure\Adapter\Hotel;

use GTS\Hotel\Infrastructure\Api\Search\Api;

class Adapter implements AdapterInterface
{

    public function __construct(private readonly Api $hotelApi)
    {
    }

    public function getRoomsAndRatePlans(int $hotelId)
    {
        $hotelDto = $this->hotelApi->findById($hotelId);
        //@todo собираем объект

        return $hotelDto;
    }
}
