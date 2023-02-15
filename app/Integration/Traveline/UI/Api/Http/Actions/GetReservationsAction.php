<?php

namespace GTS\Integration\Traveline\UI\Api\Http\Actions;

use GTS\Integration\Traveline\Domain\Api\Response\GetReservationsActionResponse;
use GTS\Integration\Traveline\Domain\Api\Response\HotelNotConnectedToChannelManagerResponse;
use GTS\Integration\Traveline\Domain\Exception\HotelNotConnectedException;
use GTS\Integration\Traveline\Infrastructure\Facade\ReservationFacadeInterface;
use GTS\Integration\Traveline\UI\Api\Http\Requests\GetReservationsActionRequest;

class GetReservationsAction
{

    public function __construct(private ReservationFacadeInterface $facade) {}

    public function handle(GetReservationsActionRequest $request)
    {
        try {
            $reservations = $this->facade->getReservations($request->getReservationId(), $request->getHotelId(), $request->getStartTime());
        } catch (HotelNotConnectedException $exception) {
            return new HotelNotConnectedToChannelManagerResponse();
        }
        return new GetReservationsActionResponse($reservations);
    }

}
