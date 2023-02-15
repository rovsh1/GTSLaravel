<?php

namespace GTS\Integration\Traveline\UI\Api\Http\Actions;

use GTS\Integration\Traveline\Domain\Api\Response\EmptySuccessResponse;
use GTS\Integration\Traveline\Infrastructure\Facade\ReservationFacadeInterface;
use GTS\Integration\Traveline\UI\Api\Http\Requests\ConfirmBookingsActionRequest;

class ConfirmBookingsAction
{
    public function __construct(private ReservationFacadeInterface $facade) {}

    public function handle(ConfirmBookingsActionRequest $request)
    {
        $this->facade->confirmReservations($request->getReservations());
        return new EmptySuccessResponse();
    }
}
