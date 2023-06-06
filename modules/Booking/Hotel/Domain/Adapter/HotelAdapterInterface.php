<?php

namespace Module\Booking\Hotel\Domain\Adapter;

interface HotelAdapterInterface
{
    public function findById(int $id): mixed;

    public function getMarkupSettings(int $id): mixed;
}
