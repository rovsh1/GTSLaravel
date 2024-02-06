<?php

declare(strict_types=1);

namespace App\Admin\Http\Controllers\Booking\Service;

use App\Admin\Http\Requests\Booking\Airport\GuestRequest;
use App\Admin\Http\Requests\Booking\Service\CarBid\UpdatePriceRequest;
use App\Admin\Support\Facades\Booking\Service\CarBidAdapter;
use App\Shared\Http\Responses\AjaxResponseInterface;
use App\Shared\Http\Responses\AjaxSuccessResponse;
use Illuminate\Http\Request;

class CarBidController
{
    public function addGuest(int $bookingId, int $carBidId, GuestRequest $request): AjaxResponseInterface
    {
        CarBidAdapter::bindGuest($bookingId, $carBidId, $request->getGuestId());

        return new AjaxSuccessResponse();
    }

    public function deleteGuest(int $bookingId, int $carBidId, GuestRequest $request): AjaxResponseInterface
    {
        CarBidAdapter::unbindGuest($bookingId, $carBidId, $request->getGuestId());

        return new AjaxSuccessResponse();
    }

    public function addCarBid(int $bookingId, Request $request): AjaxResponseInterface
    {
        CarBidAdapter::addCarBid($bookingId, $request->toArray());

        return new AjaxSuccessResponse();
    }

    public function updateCarBid(int $bookingId, int $carBidId, Request $request): AjaxResponseInterface
    {
        CarBidAdapter::updateCarBid($bookingId, $carBidId, $request->toArray());

        return new AjaxSuccessResponse();
    }

    public function removeCarBid(int $bookingId, int $carBidId): AjaxResponseInterface
    {
        CarBidAdapter::removeCarBid($bookingId, $carBidId);

        return new AjaxSuccessResponse();
    }

    public function setCarBidManualPrice(
        UpdatePriceRequest $request,
        int $bookingId,
        int $carBidId
    ): AjaxResponseInterface {
        CarBidAdapter::setManualPrice($bookingId, $carBidId, $request->getClientPrice(), $request->getSupplierPrice());

        return new AjaxSuccessResponse();
    }
}
