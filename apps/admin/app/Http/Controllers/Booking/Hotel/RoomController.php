<?php

declare(strict_types=1);

namespace App\Admin\Http\Controllers\Booking\Hotel;

use App\Admin\Http\Requests\Booking\Hotel\Room\AddRoomRequest;
use App\Admin\Http\Requests\Booking\Hotel\Room\DeleteRoomRequest;
use App\Admin\Http\Requests\Booking\Hotel\Room\Guest\RoomGuestRequest;
use App\Admin\Http\Requests\Booking\Hotel\Room\UpdateRoomRequest;
use App\Admin\Http\Requests\Booking\UpdatePriceRequest;
use App\Admin\Support\Facades\Booking\Hotel\AccommodationAdapter;
use App\Shared\Http\Responses\AjaxErrorResponse;
use App\Shared\Http\Responses\AjaxResponseInterface;
use App\Shared\Http\Responses\AjaxSuccessResponse;
use Illuminate\Http\JsonResponse;
use Module\Shared\Exception\ApplicationException;

class RoomController
{
    public function getAvailable(int $bookingId): JsonResponse
    {
        $roomDtos = AccommodationAdapter::getAvailableRooms($bookingId);

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
            AccommodationAdapter::add(
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
            AccommodationAdapter::update(
                bookingId: $id,
                accommodationId: $request->getAccommodationId(),
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
            AccommodationAdapter::delete($id, $request->getAccommodationId());
        } catch (\Throwable $e) {
            //@todo отлов доменных эксепшнов
            dd($e);
        }

        return new AjaxSuccessResponse();
    }

    public function addRoomGuest(RoomGuestRequest $request, int $id): AjaxResponseInterface
    {
        try {
            AccommodationAdapter::bindGuest($id, $request->getAccommodationId(), $request->getGuestId());
        } catch (\Throwable $e) {
            //@todo отлов доменных эксепшнов
            return new AjaxErrorResponse($e->getMessage());
        }

        return new AjaxSuccessResponse();
    }

    public function deleteRoomGuest(RoomGuestRequest $request, int $id): AjaxResponseInterface
    {
        try {
            AccommodationAdapter::unbindGuest($id, $request->getAccommodationId(), $request->getGuestId(),);
        } catch (\Throwable $e) {
            //@todo отлов доменных эксепшнов
            return new AjaxErrorResponse($e->getMessage());
        }

        return new AjaxSuccessResponse();
    }

    public function updatePrice(UpdatePriceRequest $request, int $id, int $accommodationId): AjaxResponseInterface
    {
        try {
            AccommodationAdapter::updatePrice(
                $id,
                $accommodationId,
                $request->getSupplierPrice(),
                $request->getClientPrice(),
            );
        } catch (ApplicationException $e) {
            return new AjaxErrorResponse($e->getMessage());
        }

        return new AjaxSuccessResponse();
    }
}
