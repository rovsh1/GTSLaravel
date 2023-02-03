<?php

namespace GTS\Hotel\UI\Admin\Http\Actions\Reservation;

use GTS\Hotel\Infrastructure\Facade\ReservationFacadeInterface;

class ReserveQuota
{

    public function __construct(private ReservationFacadeInterface $facade) { }

    public function handle($roomId, $date)
    {
        $result = $this->facade->reserveQuota($roomId, $date);

        return [];
    }
}
