<?php

declare(strict_types=1);

namespace App\Admin\Http\Controllers\Booking\Service;

use App\Admin\Http\Requests\Booking\Airport\GuestRequest;
use App\Admin\Http\Requests\Booking\UpdateDetailsFieldRequest;
use App\Admin\Http\Resources\Booking\ServiceType;
use App\Admin\Support\Facades\Booking\Service\DetailsAdapter;
use App\Shared\Http\Responses\AjaxResponseInterface;
use App\Shared\Http\Responses\AjaxSuccessResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Sdk\Shared\Enum\ServiceTypeEnum;

class DetailsController
{
    public function getTypes(Request $request): JsonResponse
    {
        return response()->json(
            ServiceType::collection(ServiceTypeEnum::getWithoutHotel())
        );
    }

    public function updateField(int $bookingId, UpdateDetailsFieldRequest $request): AjaxResponseInterface
    {
        DetailsAdapter::updateDetailsField($bookingId, $request->getField(), $request->getValue());

        return new AjaxSuccessResponse();
    }

    public function addGuest(int $bookingId, GuestRequest $request): AjaxResponseInterface
    {
        DetailsAdapter::bindGuest($bookingId, $request->getGuestId());

        return new AjaxSuccessResponse();
    }

    public function deleteGuest(int $bookingId, GuestRequest $request): AjaxResponseInterface
    {
        DetailsAdapter::unbindGuest($bookingId, $request->getGuestId());

        return new AjaxSuccessResponse();
    }

    public function addCarBid(int $bookingId, Request $request): AjaxResponseInterface
    {
        DetailsAdapter::addCarBid($bookingId, $request->toArray());

        return new AjaxSuccessResponse();
    }

    public function updateCarBid(int $bookingId, string $carBidId, Request $request): AjaxResponseInterface
    {
        DetailsAdapter::updateCarBid($bookingId, $carBidId, $request->toArray());

        return new AjaxSuccessResponse();
    }

    public function removeCarBid(int $bookingId, string $carBidId): AjaxResponseInterface
    {
        DetailsAdapter::removeCarBid($bookingId, $carBidId);

        return new AjaxSuccessResponse();
    }
}
