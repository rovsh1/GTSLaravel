<?php

namespace GTS\Integration\Traveline\Domain\Adapter;

interface HotelAdapterInterface
{
    public function getHotelById(int $hotelId);

    public function getRoomsAndRatePlans(int $hotelId);

    public function updateQuotasAndPlans();
}
