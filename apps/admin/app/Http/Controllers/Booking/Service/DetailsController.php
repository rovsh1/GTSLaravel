<?php

declare(strict_types=1);

namespace App\Admin\Http\Controllers\Booking\Service;

use App\Admin\Http\Requests\Booking\Airport\GuestRequest;
use App\Admin\Http\Requests\Booking\UpdateDetailsFieldRequest;
use App\Admin\Http\Resources\Booking\ServiceType;
use App\Admin\Support\Facades\Booking\Service\DetailsAdapter;
use App\Shared\Http\Responses\AjaxErrorResponse;
use App\Shared\Http\Responses\AjaxResponseInterface;
use App\Shared\Http\Responses\AjaxSuccessResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Module\Shared\Enum\ServiceTypeEnum;
use Module\Shared\Exception\ApplicationException;

class DetailsController
{
    public function getTypes(Request $request): JsonResponse
    {
        return response()->json(
            ServiceType::collection(ServiceTypeEnum::cases())
        );
    }

    public function updateField(int $bookingId, UpdateDetailsFieldRequest $request): AjaxResponseInterface
    {
        try {
            DetailsAdapter::updateDetailsField($bookingId, $request->getField(), $request->getValue());
        } catch (ApplicationException $e) {
            return new AjaxErrorResponse($e->getMessage());
        }

        return new AjaxSuccessResponse();
    }

    public function addGuest(int $bookingId, GuestRequest $request): AjaxResponseInterface
    {
        try {
            DetailsAdapter::bindGuest($bookingId, $request->getGuestId());
        } catch (ApplicationException $e) {
            return new AjaxErrorResponse($e->getMessage());
        }

        return new AjaxSuccessResponse();
    }

    public function deleteGuest(int $bookingId, GuestRequest $request): AjaxResponseInterface
    {
        try {
            DetailsAdapter::unbindGuest($bookingId, $request->getGuestId());
        } catch (ApplicationException $e) {
            return new AjaxErrorResponse($e->getMessage());
        }

        return new AjaxSuccessResponse();
    }

    public function addCarBid(int $bookingId, Request $request): AjaxResponseInterface
    {
        try {
            DetailsAdapter::addCarBid($bookingId, $request->toArray());
        } catch (ApplicationException $e) {
            return new AjaxErrorResponse($e->getMessage());
        }

        return new AjaxSuccessResponse();
    }

    public function updateCarBid(int $bookingId, string $carBidId, Request $request): AjaxResponseInterface
    {
        try {
            DetailsAdapter::updateCarBid($bookingId, $carBidId, $request->toArray());
        } catch (ApplicationException $e) {
            return new AjaxErrorResponse($e->getMessage());
        }

        return new AjaxSuccessResponse();
    }

    public function removeCarBid(int $bookingId, string $carBidId): AjaxResponseInterface
    {
        try {
            DetailsAdapter::removeCarBid($bookingId, $carBidId);
        } catch (ApplicationException $e) {
            return new AjaxErrorResponse($e->getMessage());
        }

        return new AjaxSuccessResponse();
    }
}
