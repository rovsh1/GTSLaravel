<?php

namespace Pkg\App\Traveline\Http\Actions;

use Pkg\App\Traveline\Http\Request\ConfirmBookingsActionRequest;
use Pkg\App\Traveline\Http\Response\EmptySuccessResponse;
use Pkg\App\Traveline\Http\Response\ErrorResponse;
use Pkg\Supplier\Traveline\UseCase\ConfirmReservations;

class ConfirmBookingsAction
{
    public function handle(ConfirmBookingsActionRequest $request)
    {
        $errors = app(ConfirmReservations::class)->execute($request->getReservations());
        if (empty($errors)) {
            return new EmptySuccessResponse();
        }

        return new ErrorResponse($errors);
    }
}
