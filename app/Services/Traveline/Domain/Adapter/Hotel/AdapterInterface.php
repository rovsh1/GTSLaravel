<?php

namespace GTS\Services\Traveline\Domain\Adapter\Hotel;

interface AdapterInterface
{
    public function getRoomsAndRatePlans(int $hotelId);

    public function updateQuotasAndPlans();
}
