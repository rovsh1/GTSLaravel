<?php

namespace GTS\Services\Traveline\UI\Api\Http\Actions;

use GTS\Services\Traveline\Infrastructure\Facade\ReservationFacadeInterface;
use GTS\Services\Traveline\UI\Api\Http\Requests\GetReservationsActionRequest;

class GetReservationsAction
{

    public function __construct(private ReservationFacadeInterface $facade) {}

    public function handle(GetReservationsActionRequest $request)
    {
        return $this->facade->getReservations($request->getReservationId(), $request->getHotelId(), $request->getStartTime());
    }

}
