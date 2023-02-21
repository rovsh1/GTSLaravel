<?php

namespace Module\Integration\Traveline\Infrastructure\Facade;

use Module\Integration\Traveline\Application\Dto\HotelDto;

interface HotelFacadeInterface
{
    public function getRoomsAndRatePlans(int $hotelId): HotelDto;

    public function updateQuotasAndPlans(int $hotelId, array $updates);
}
