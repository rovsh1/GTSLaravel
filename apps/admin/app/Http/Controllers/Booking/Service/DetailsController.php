<?php

declare(strict_types=1);

namespace App\Admin\Http\Controllers\Booking\Service;

use App\Admin\Http\Requests\Booking\Airport\GuestRequest;
use App\Admin\Support\Facades\Booking\ServiceAdapter;
use App\Core\Support\Http\Responses\AjaxErrorResponse;
use App\Core\Support\Http\Responses\AjaxResponseInterface;
use App\Core\Support\Http\Responses\AjaxSuccessResponse;
use Illuminate\Http\Request;
use Module\Shared\Exception\ApplicationException;

class DetailsController
{
    public function update(int $bookingId, Request $request): AjaxResponseInterface
    {
        return new AjaxSuccessResponse();
    }

    public function addGuest(int $bookingId, GuestRequest $request): AjaxResponseInterface
    {
        try {
            ServiceAdapter::bindGuest($bookingId, $request->getGuestId());
        } catch (ApplicationException $e) {
            return new AjaxErrorResponse($e->getMessage());
        }

        return new AjaxSuccessResponse();
    }

    public function deleteGuest(int $bookingId, GuestRequest $request): AjaxResponseInterface
    {
        try {
            ServiceAdapter::unbindGuest($bookingId, $request->getGuestId());
        } catch (ApplicationException $e) {
            return new AjaxErrorResponse($e->getMessage());
        }

        return new AjaxSuccessResponse();
    }

    public function updateCars(int $bookingId, Request $request): AjaxResponseInterface
    {
        try {
            ServiceAdapter::updateCars($bookingId, $request->toArray());
        } catch (ApplicationException $e) {
            return new AjaxErrorResponse($e->getMessage());
        }

        return new AjaxSuccessResponse();
    }
}
