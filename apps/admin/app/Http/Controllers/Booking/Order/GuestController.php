<?php

declare(strict_types=1);

namespace App\Admin\Http\Controllers\Booking\Order;

use App\Admin\Http\Controllers\Controller;
use App\Admin\Http\Requests\Order\Guest\AddRequest;
use App\Admin\Http\Requests\Order\Guest\UpdateRequest;
use App\Admin\Support\Facades\Booking\Hotel\RoomAdapter;
use App\Admin\Support\Facades\Booking\OrderAdapter;
use App\Admin\Support\Facades\Booking\Service\DetailsAdapter;
use App\Shared\Http\Responses\AjaxErrorResponse;
use App\Shared\Http\Responses\AjaxResponseInterface;
use App\Shared\Http\Responses\AjaxSuccessResponse;
use Illuminate\Http\JsonResponse;
use Module\Shared\Exception\ApplicationException;

class GuestController extends Controller
{
    public function list(int $orderId): JsonResponse
    {
        $guests = OrderAdapter::getGuests($orderId);

        return response()->json($guests);
    }

    public function addGuest(int $orderId, AddRequest $request): JsonResponse|AjaxResponseInterface
    {
        try {
            $guest = OrderAdapter::addGuest(
                orderId: $orderId,
                fullName: $request->getFullName(),
                countryId: $request->getCountryId(),
                isAdult: $request->getIsAdult(),
                gender: $request->getGender(),
                age: $request->getAge()
            );
            if ($request->hotelBookingId() !== null) {
                RoomAdapter::bindRoomGuest($request->hotelBookingId(), $request->hotelBookingRoomId(), $guest->id);
            }

            if ($request->airportBookingId() !== null) {
                DetailsAdapter::bindGuest($request->airportBookingId(), $guest->id);
            }
        } catch (ApplicationException $e) {
            return new AjaxErrorResponse($e->getMessage());
        }

        return response()->json($guest);
    }

    public function updateGuest(int $orderId, int $guestId, UpdateRequest $request): AjaxResponseInterface
    {
        OrderAdapter::updateGuest(
            guestId: $guestId,
            fullName: $request->getFullName(),
            countryId: $request->getCountryId(),
            isAdult: $request->getIsAdult(),
            gender: $request->getGender(),
            age: $request->getAge()
        );

        return new AjaxSuccessResponse();
    }

    public function deleteGuest(int $orderId, int $guestId): AjaxResponseInterface
    {
        OrderAdapter::deleteGuest($guestId);

        return new AjaxSuccessResponse();
    }
}
