<?php

namespace GTS\Services\Traveline\Infrastructure\Adapter\Hotel;

use GTS\Hotel\Infrastructure\Facade\Info\FacadeInterface;

class Adapter implements AdapterInterface
{
    public function __construct(private readonly FacadeInterface $hotelApi) {}

    public function getRoomsAndRatePlans(int $hotelId)
    {
        $hotelDto = $this->hotelApi->findById($hotelId);
        $roomsDto = $this->hotelApi->getRoomsWithPriceRateByHotelId($hotelId);
        dd($roomsDto);
        return $hotelDto;
    }

    public function updateQuotasAndPlans(): void
    {
        // TODO: Implement updateQuotasAndPlans() method.
    }
}
