<?php

namespace Module\Booking\Hotel\Infrastructure\Adapter;

use Module\Booking\Hotel\Domain\Adapter\HotelRoomAdapterInterface;
use Module\Shared\Infrastructure\Adapter\AbstractModuleAdapter;

class HotelRoomAdapter extends AbstractModuleAdapter implements HotelRoomAdapterInterface
{

    public function findById(int $id): mixed
    {
        $roomDto = $this->request('getRoom', ['id' => $id]);

        return $roomDto;
    }

    protected function getModuleKey(): string
    {
        return 'Hotel';
    }
}
