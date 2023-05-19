<?php

namespace Module\Booking\Hotel\Infrastructure\Adapter;

use Module\Booking\Hotel\Domain\Adapter\HotelAdapterInterface;
use Module\Shared\Infrastructure\Adapter\AbstractModuleAdapter;

class HotelAdapter extends AbstractModuleAdapter implements HotelAdapterInterface
{

    public function findById(int $id)
    {
        $hotelDto = $this->request('findById', ['id' => $id]);

        return $hotelDto;
    }

    protected function getModuleKey(): string
    {
        return 'Hotel';
    }
}
