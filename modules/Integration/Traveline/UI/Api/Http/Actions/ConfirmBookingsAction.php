<?php

namespace Module\Integration\Traveline\UI\Api\Http\Actions;

use Module\Integration\Traveline\Domain\Api\Response\EmptySuccessResponse;
use Module\Integration\Traveline\Infrastructure\Facade\ReservationFacadeInterface;
use Module\Integration\Traveline\UI\Api\Http\Requests\ConfirmBookingsActionRequest;

class ConfirmBookingsAction
{
    public function __construct(private ReservationFacadeInterface $facade) {}

    public function handle(ConfirmBookingsActionRequest $request)
    {
        $this->facade->confirmReservations($request->getReservations());
        return new EmptySuccessResponse();
    }
}
