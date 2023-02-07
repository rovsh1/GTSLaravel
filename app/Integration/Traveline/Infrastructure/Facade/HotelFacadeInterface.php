<?php

namespace GTS\Integration\Traveline\Infrastructure\Facade;

interface HotelFacadeInterface
{
    public function getRoomsAndRatePlans(int $hotelId);

    public function updateQuotasAndPlans();
}
