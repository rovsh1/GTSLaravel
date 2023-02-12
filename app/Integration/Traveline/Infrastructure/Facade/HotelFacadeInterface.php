<?php

namespace GTS\Integration\Traveline\Infrastructure\Facade;

use GTS\Integration\Traveline\Application\Dto\HotelDto;

interface HotelFacadeInterface
{
    public function getRoomsAndRatePlans(int $hotelId): HotelDto;

    public function updateQuotasAndPlans(int $hotelId, array $updates);
}
