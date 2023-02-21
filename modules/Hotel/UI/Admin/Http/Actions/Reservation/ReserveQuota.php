<?php

namespace Module\Hotel\UI\Admin\Http\Actions\Reservation;

use Module\Hotel\Infrastructure\Facade\RoomQuotaFacadeInterface;

class ReserveQuota
{

    public function __construct(private RoomQuotaFacadeInterface $facade) { }

    public function handle($roomId, $date)
    {
        //этот метод юзать нельзя, он для других целей
//        $result = $this->facade->updateRoomQuota($roomId, $date);

        return [];
    }
}
