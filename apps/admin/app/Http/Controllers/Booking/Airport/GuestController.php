<?php

declare(strict_types=1);

namespace App\Admin\Http\Controllers\Booking\Airport;

use App\Admin\Http\Controllers\Controller;
use App\Admin\Http\Requests\Booking\Airport\GuestRequest;
use App\Admin\Support\Facades\Booking\AirportAdapter;
use App\Shared\Http\Responses\AjaxResponseInterface;
use App\Shared\Http\Responses\AjaxSuccessResponse;

class GuestController extends Controller
{
    public function addGuest(int $bookingId, GuestRequest $request): AjaxResponseInterface
    {
        AirportAdapter::bindGuest($bookingId, $request->getGuestId());

        return new AjaxSuccessResponse();
    }

    public function deleteGuest(int $bookingId, GuestRequest $request): AjaxResponseInterface
    {
        AirportAdapter::unbindGuest($bookingId, $request->getGuestId());

        return new AjaxSuccessResponse();
    }
}
