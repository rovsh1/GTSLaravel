<?php

declare(strict_types=1);

namespace App\Admin\Http\Controllers\Booking\Hotel;

use App\Admin\Http\Requests\Booking\Hotel\Room\AddRoomRequest;
use App\Admin\Http\Requests\Booking\Hotel\Room\DeleteRoomRequest;
use App\Admin\Http\Requests\Booking\Hotel\Room\Guest\RoomGuestRequest;
use App\Admin\Http\Requests\Booking\Hotel\Room\UpdateRoomRequest;
use App\Admin\Http\Requests\Booking\Hotel\UpdatePriceRequest;
use App\Admin\Support\Facades\Booking\Hotel\DetailsAdapter;
use App\Admin\Support\Facades\Booking\Hotel\PriceAdapter;
use App\Admin\Support\Facades\Booking\Hotel\RoomAdapter;
use App\Core\Support\Http\Responses\AjaxErrorResponse;
use App\Core\Support\Http\Responses\AjaxResponseInterface;
use App\Core\Support\Http\Responses\AjaxSuccessResponse;
use Illuminate\Http\JsonResponse;
use Module\Shared\Exception\ApplicationException;

class RoomController
{
    public function getAvailable(int $bookingId): JsonResponse
    {
        $roomDtos = RoomAdapter::getAvailableRooms($bookingId);

        $roomResources = array_map(fn(mixed $roomDto) => [
            'id' => $roomDto->id,
            'hotel_id' => $roomDto->hotelId,
            'name' => $roomDto->name,
            'rooms_number' => $roomDto->roomsCount,
            'guests_count' => $roomDto->guestsCount,
        ], $roomDtos);

        return response()->json($roomResources);
    }

    public function addRoom(AddRoomRequest $request, int $id): AjaxResponseInterface
    {
        try {
            DetailsAdapter::addRoom(
                bookingId: $id,
                roomId: $request->getRoomId(),
                rateId: $request->getRateId(),
                isResident: $request->getIsResident(),
                earlyCheckIn: $request->getEarlyCheckIn(),
                lateCheckOut: $request->getLateCheckOut(),
                note: $request->getNote(),
                discount: $request->getDiscount()
            );
        } catch (ApplicationException $e) {
            return new AjaxErrorResponse($e->getMessage());
        }

        return new AjaxSuccessResponse();
    }

    public function updateRoom(UpdateRoomRequest $request, int $id): AjaxResponseInterface
    {
        try {
            DetailsAdapter::updateRoom(
                bookingId: $id,
                roomBookingId: $request->getRoomBookingId(),
                roomId: $request->getRoomId(),
                rateId: $request->getRateId(),
                isResident: $request->getIsResident(),
                earlyCheckIn: $request->getEarlyCheckIn(),
                lateCheckOut: $request->getLateCheckOut(),
                note: $request->getNote(),
                discount: $request->getDiscount()
            );
        } catch (ApplicationException $e) {
            return new AjaxErrorResponse($e->getMessage());
        }

        return new AjaxSuccessResponse();
    }

    public function deleteRoom(DeleteRoomRequest $request, int $id): AjaxResponseInterface
    {
        try {
            DetailsAdapter::deleteRoom($id, $request->getRoomBookingId());
        } catch (\Throwable $e) {
            //@todo отлов доменных эксепшнов
            dd($e);
        }

        return new AjaxSuccessResponse();
    }

    public function addRoomGuest(RoomGuestRequest $request, int $id): AjaxResponseInterface
    {
        try {
            DetailsAdapter::bindRoomGuest($id, $request->getRoomBookingId(), $request->getGuestId());
        } catch (\Throwable $e) {
            //@todo отлов доменных эксепшнов
            return new AjaxErrorResponse($e->getMessage());
        }

        return new AjaxSuccessResponse();
    }

    public function deleteRoomGuest(RoomGuestRequest $request, int $id): AjaxResponseInterface
    {
        try {
            DetailsAdapter::unbindRoomGuest($id, $request->getRoomBookingId(), $request->getGuestId(),);
        } catch (\Throwable $e) {
            //@todo отлов доменных эксепшнов
            return new AjaxErrorResponse($e->getMessage());
        }

        return new AjaxSuccessResponse();
    }

    public function updatePrice(UpdatePriceRequest $request, int $id, int $roomBookingId): AjaxResponseInterface
    {
        try {
            PriceAdapter::updateRoomPrice(
                $id,
                $roomBookingId,
                $request->getGrossPrice(),
                $request->getNetPrice()
            );
        } catch (ApplicationException $e) {
            return new AjaxErrorResponse($e->getMessage());
        }

        return new AjaxSuccessResponse();
    }
}
