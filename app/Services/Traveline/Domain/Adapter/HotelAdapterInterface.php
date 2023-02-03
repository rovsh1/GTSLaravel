<?php

namespace GTS\Services\Traveline\Domain\Adapter;

interface HotelAdapterInterface
{
    public function getRoomsAndRatePlans(int $hotelId);

    public function updateQuotasAndPlans();
}
