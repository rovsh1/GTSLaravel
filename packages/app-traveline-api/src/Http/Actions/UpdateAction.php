<?php

namespace Pkg\App\Traveline\Http\Actions;

use Pkg\App\Traveline\Http\Request\UpdateActionRequest;
use Pkg\App\Traveline\Http\Response\EmptySuccessResponse;
use Pkg\App\Traveline\Http\Response\ErrorResponse;
use Pkg\App\Traveline\Http\Response\HotelNotConnectedToChannelManagerResponse;
use Pkg\Supplier\Traveline\Dto\Response\Error\InvalidRateAccomodation;
use Pkg\Supplier\Traveline\Exception\HotelNotConnectedException;
use Pkg\Supplier\Traveline\Exception\InvalidHotelRoomCode;
use Pkg\Supplier\Traveline\UseCase\UpdateQuotasAndPlans;

class UpdateAction
{
    public function handle(UpdateActionRequest $request)
    {
        try {
            $errors = app(UpdateQuotasAndPlans::class)->execute($request->getHotelId(), $request->getUpdates());
        } catch (InvalidHotelRoomCode) {
            return new ErrorResponse([new InvalidRateAccomodation()]);
        } catch (HotelNotConnectedException) {
            return new HotelNotConnectedToChannelManagerResponse();
        }
        if (empty($errors)) {
            return new EmptySuccessResponse();
        }

        return new ErrorResponse($errors);
    }

}
