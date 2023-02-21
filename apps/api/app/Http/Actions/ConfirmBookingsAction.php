<?php

namespace App\Api\Http\Actions;

use App\Api\Http\Requests\ConfirmBookingsActionRequest;
use Module\Integration\Traveline\Domain\Api\Response\EmptySuccessResponse;
use Module\Integration\Traveline\Infrastructure\Facade\ReservationFacadeInterface;

class ConfirmBookingsAction
{
    public function __construct(private ReservationFacadeInterface $facade) {}

    public function handle(ConfirmBookingsActionRequest $request)
    {
        $this->facade->confirmReservations($request->getReservations());
        return new EmptySuccessResponse();
    }
}
