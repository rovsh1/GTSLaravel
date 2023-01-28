<?php

namespace GTS\Services\Traveline\Infrastructure\Adapter\Hotel;

interface AdapterInterface
{

    public function getRoomsAndRatePlans(int $hotelId);

    public function updateQuotasAndPlans();

}
