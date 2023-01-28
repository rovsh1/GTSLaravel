<?php

namespace GTS\Services\Traveline\Infrastructure\Facade\Hotel;

interface FacadeInterface
{
    public function getRoomsAndRatePlans(int $hotelId);

    public function updateQuotasAndPlans();
}
