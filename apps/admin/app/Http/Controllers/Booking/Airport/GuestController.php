<?php

declare(strict_types=1);

namespace App\Admin\Http\Controllers\Booking\Airport;

use App\Admin\Http\Controllers\Controller;
use App\Admin\Http\Requests\Booking\Airport\GuestRequest;
use App\Admin\Support\Facades\Booking\AirportAdapter;
use App\Core\Support\Http\Responses\AjaxErrorResponse;
use App\Core\Support\Http\Responses\AjaxResponseInterface;
use App\Core\Support\Http\Responses\AjaxSuccessResponse;
use Module\Shared\Exception\ApplicationException;

class GuestController extends Controller
{
    public function addGuest(int $bookingId, GuestRequest $request): AjaxResponseInterface
    {
        try {
            AirportAdapter::bindGuest($bookingId, $request->getGuestId());
        } catch (ApplicationException $e) {
            return new AjaxErrorResponse($e->getMessage());
        }

        return new AjaxSuccessResponse();
    }

    public function deleteGuest(int $bookingId, GuestRequest $request): AjaxResponseInterface
    {
        try {
            AirportAdapter::unbindGuest($bookingId, $request->getGuestId());
        } catch (ApplicationException $e) {
            return new AjaxErrorResponse($e->getMessage());
        }

        return new AjaxSuccessResponse();
    }
}
