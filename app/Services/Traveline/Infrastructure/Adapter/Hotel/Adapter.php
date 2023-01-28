<?php

namespace GTS\Services\Traveline\Infrastructure\Adapter\Hotel;

use GTS\Hotel\Infrastructure\Facade\Search\Facade;

class Adapter implements AdapterInterface
{

    public function __construct(private readonly Facade $hotelApi)
    {
    }

    public function getRoomsAndRatePlans(int $hotelId)
    {
        $hotelDto = $this->hotelApi->findById($hotelId);
        //@todo собираем объект

        return $hotelDto;
    }

    public function updateQuotasAndPlans(): void
    {
        // TODO: Implement updateQuotasAndPlans() method.
    }
}
