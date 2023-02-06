<?php

namespace GTS\Services\Integration\Traveline\Infrastructure\Facade;

interface HotelFacadeInterface
{
    public function getRoomsAndRatePlans(int $hotelId);

    public function updateQuotasAndPlans();
}
