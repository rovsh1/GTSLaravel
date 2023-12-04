<?php

namespace Supplier\Traveline\Port\Controllers;

use Sdk\Module\PortGateway\Request;
use Supplier\Traveline\Application\Service\HotelFinder;
use Supplier\Traveline\Application\Service\QuotaAndPriceUpdater;
use Supplier\Traveline\Domain\Api\Response\EmptySuccessResponse;
use Supplier\Traveline\Domain\Api\Response\ErrorResponse;
use Supplier\Traveline\Domain\Api\Response\GetRoomsAndRatePlansActionResponse;
use Supplier\Traveline\Domain\Api\Response\HotelNotConnectedToChannelManagerResponse;
use Supplier\Traveline\Domain\Api\Response\HotelNotExistInChannelResponse;
use Supplier\Traveline\Domain\Exception\HotelNotConnectedException;

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
        if (empty($errors)) {
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
