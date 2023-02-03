<?php

namespace GTS\Services\Traveline\Infrastructure\Facade;

interface HotelFacadeInterface
{
    public function getRoomsAndRatePlans(int $hotelId);

    public function updateQuotasAndPlans();
}
