<?php

namespace GTS\Hotel\UI\Admin\Http\Actions\Reservation;

use GTS\Hotel\Infrastructure\Facade\Reservation\FacadeInterface;

class ReserveQuota
{

    public function __construct(private FacadeInterface $facade) { }

    public function handle($roomId, $date)
    {
        $result = $this->facade->reserveQuota($roomId, $date);

        return [];
    }
}
