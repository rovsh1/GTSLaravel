<?php

namespace GTS\Services\Traveline\UI\Api\Http\Actions;

use GTS\Services\Traveline\Infrastructure\Facade\Reservation\FacadeInterface;
use GTS\Services\Traveline\UI\Api\Http\Requests\ConfirmBookingsActionRequest;

class ConfirmBookingsAction
{
    public function __construct(private FacadeInterface $facade) {}

    public function handle(ConfirmBookingsActionRequest $request)
    {
        //@todo тут приходит пачка броней, где обрабатывать пачку?
        $this->facade->confirmReservations();
    }
}
