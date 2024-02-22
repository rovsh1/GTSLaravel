<?php

namespace Pkg\Supplier\Traveline\Http\Actions;

use Pkg\Supplier\Traveline\Exception\HotelNotConnectedException;
use Pkg\Supplier\Traveline\Exception\InvalidHotelRoomCode;
use Pkg\Supplier\Traveline\Http\Request\UpdateActionRequest;
use Pkg\Supplier\Traveline\Http\Response\EmptySuccessResponse;
use Pkg\Supplier\Traveline\Http\Response\Error\InvalidRateAccomodation;
use Pkg\Supplier\Traveline\Http\Response\ErrorResponse;
use Pkg\Supplier\Traveline\Http\Response\HotelNotConnectedToChannelManagerResponse;
use Pkg\Supplier\Traveline\Service\QuotaAndPriceUpdater;

class UpdateAction
{
    public function __construct(private QuotaAndPriceUpdater $quotaUpdaterService) {}

    public function handle(UpdateActionRequest $request)
    {
        try {
            $errors = $this->quotaUpdaterService->updateQuotasAndPlans($request->getHotelId(), $request->getUpdates());
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
