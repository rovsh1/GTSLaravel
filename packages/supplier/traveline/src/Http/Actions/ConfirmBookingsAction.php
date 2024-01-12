<?php

namespace Pkg\Supplier\Traveline\Http\Actions;

use Pkg\Supplier\Traveline\Http\Request\ConfirmBookingsActionRequest;
use Pkg\Supplier\Traveline\Http\Response\EmptySuccessResponse;
use Pkg\Supplier\Traveline\Http\Response\ErrorResponse;
use Pkg\Supplier\Traveline\Service\BookingService;

class ConfirmBookingsAction
{
    public function __construct(private readonly BookingService $bookingService) {}

    public function handle(ConfirmBookingsActionRequest $request)
    {
        $errors = $this->bookingService->confirmReservations($request->getReservations());
        if (empty($errors)) {
            return new EmptySuccessResponse();
        }

        return new ErrorResponse($errors);
    }
}
