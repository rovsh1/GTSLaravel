<?php

namespace Module\Integration\Traveline\UI\Api\Http\Actions;

use Module\Integration\Traveline\Domain\Api\Response\GetReservationsActionResponse;
use Module\Integration\Traveline\Domain\Api\Response\HotelNotConnectedToChannelManagerResponse;
use Module\Integration\Traveline\Domain\Exception\HotelNotConnectedException;
use Module\Integration\Traveline\Infrastructure\Facade\ReservationFacadeInterface;
use Module\Integration\Traveline\UI\Api\Http\Requests\GetReservationsActionRequest;

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
