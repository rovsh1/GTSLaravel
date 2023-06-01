<?php

namespace Module\Booking\Hotel\Domain\Adapter;

interface HotelAdapterInterface
{
    public function getMarkupSettings(int $id): mixed;
}
