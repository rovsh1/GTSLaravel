<?php

namespace GTS\Hotel\UI\Admin\Http\Actions\Reservation;

use GTS\Hotel\Infrastructure\Api\Reservation\ApiInterface;

class ReserveQuota
{

    public function __construct(private ApiInterface $api) { }

    public function handle($roomId, $date)
    {
        $result = $this->api->reserveQuota($roomId, $date);

        return [];
    }
}
