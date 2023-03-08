<?php

namespace Module\Integration\Traveline\Port\Controllers;

use Custom\Framework\Port\Request;
use Module\Integration\Traveline\Application\Service\HotelFinder;
use Module\Integration\Traveline\Application\Service\QuotaAndPriceUpdater;
use Module\Integration\Traveline\Domain\Api\Response\EmptySuccessResponse;
use Module\Integration\Traveline\Domain\Api\Response\ErrorResponse;
use Module\Integration\Traveline\Domain\Api\Response\GetRoomsAndRatePlansActionResponse;
use Module\Integration\Traveline\Domain\Api\Response\HotelNotConnectedToChannelManagerResponse;
use Module\Integration\Traveline\Domain\Api\Response\HotelNotExistInChannelResponse;
use Module\Integration\Traveline\Domain\Exception\HotelNotConnectedException;

class HotelController
{
    public function __construct(
        private HotelFinder          $hotelFinder,
        private QuotaAndPriceUpdater $quotaUpdaterService
    ) {}

    public function update(Request $request)
    {
        $request->validate([
            'hotel_id' => 'required|numeric',
            'updates' => 'required|array'
        ]);

        try {
            $errors = $this->quotaUpdaterService->updateQuotasAndPlans($request->hotel_id, $request->updates);
        } catch (HotelNotConnectedException $exception) {
            return new HotelNotConnectedToChannelManagerResponse();
        }
        $filteredErrors = array_filter($errors);
        if (empty($filteredErrors)) {
            return new EmptySuccessResponse();
        }
        return new ErrorResponse($errors);
    }

    public function getRoomsAndRatePlans(Request $request): mixed
    {
        $request->validate([
            'hotel_id' => 'required|numeric',
        ]);

        try {
            $roomsAndRatePlans = $this->hotelFinder->getHotelRoomsAndRatePlans($request->hotel_id);
        } catch (HotelNotConnectedException $exception) {
            return new HotelNotExistInChannelResponse();
        }
        return new GetRoomsAndRatePlansActionResponse($roomsAndRatePlans);
    }

}
