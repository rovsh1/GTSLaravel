<?php

declare(strict_types=1);

namespace App\Admin\Http\Controllers\Booking\Hotel;

use App\Admin\Http\Requests\Booking\AddRoomGuestRequest;
use App\Admin\Http\Requests\Booking\AddRoomRequest;
use App\Admin\Http\Requests\Booking\DeleteRoomGuestRequest;
use App\Admin\Http\Requests\Booking\DeleteRoomRequest;
use App\Admin\Http\Requests\Booking\UpdateRoomGuestRequest;
use App\Admin\Http\Requests\Booking\UpdateRoomRequest;
use App\Admin\Support\Facades\Booking\HotelAdapter;
use App\Core\Support\Http\Responses\AjaxErrorResponse;
use App\Core\Support\Http\Responses\AjaxResponseInterface;
use App\Core\Support\Http\Responses\AjaxSuccessResponse;

class RoomController
{
    public function addRoom(AddRoomRequest $request, int $id): AjaxResponseInterface
    {
        try {
            HotelAdapter::addRoom(
                bookingId: $id,
                roomId: $request->getRoomId(),
                rateId: $request->getRateId(),
                isResident: $request->getIsResident(),
                earlyCheckIn: $request->getEarlyCheckIn(),
                lateCheckOut: $request->getLateCheckOut(),
                status: $request->getStatus(),
                note: $request->getNote(),
                discount: $request->getDiscount()
            );
        } catch (\Throwable $e) {
            //@todo отлов доменных эксепшнов
            dd($e);
        }

        return new AjaxSuccessResponse();
    }

    public function updateRoom(UpdateRoomRequest $request, int $id): AjaxResponseInterface
    {
        try {
            HotelAdapter::updateRoom(
                bookingId: $id,
                roomIndex: $request->getRoomIndex(),
                roomId: $request->getRoomId(),
                rateId: $request->getRateId(),
                status: $request->getStatus(),
                isResident: $request->getIsResident(),
                earlyCheckIn: $request->getEarlyCheckIn(),
                lateCheckOut: $request->getLateCheckOut(),
                note: $request->getNote(),
                discount: $request->getDiscount()
            );
        } catch (\Throwable $e) {
            //@todo отлов доменных эксепшнов
            dd($e);
        }

        return new AjaxSuccessResponse();
    }

    public function deleteRoom(DeleteRoomRequest $request, int $id): AjaxResponseInterface
    {
        try {
            HotelAdapter::deleteRoom($id, $request->getRoomIndex());
        } catch (\Throwable $e) {
            //@todo отлов доменных эксепшнов
            dd($e);
        }

        return new AjaxSuccessResponse();
    }

    public function addRoomGuest(AddRoomGuestRequest $request, int $id): AjaxResponseInterface
    {
        try {
            HotelAdapter::addRoomGuest(
                $id,
                $request->getRoomIndex(),
                $request->getFullName(),
                $request->getCountryId(),
                $request->getGender(),
                $request->getIsAdult(),
                $request->getAge(),
            );
        } catch (\Throwable $e) {
            //@todo отлов доменных эксепшнов
            return new AjaxErrorResponse($e->getMessage());
        }

        return new AjaxSuccessResponse();
    }

    public function updateRoomGuest(UpdateRoomGuestRequest $request, int $id): AjaxResponseInterface
    {
        try {
            HotelAdapter::updateRoomGuest(
                $id,
                $request->getRoomIndex(),
                $request->getGuestIndex(),
                $request->getFullName(),
                $request->getCountryId(),
                $request->getGender(),
                $request->getIsAdult(),
                $request->getAge(),
            );
        } catch (\Throwable $e) {
            //@todo отлов доменных эксепшнов
            return new AjaxErrorResponse($e->getMessage());
        }

        return new AjaxSuccessResponse();
    }

    public function deleteRoomGuest(DeleteRoomGuestRequest $request, int $id): AjaxResponseInterface
    {
        try {
            HotelAdapter::deleteRoomGuest($id, $request->getRoomIndex(), $request->getGuestIndex(),);
        } catch (\Throwable $e) {
            //@todo отлов доменных эксепшнов
            return new AjaxErrorResponse($e->getMessage());
        }

        return new AjaxSuccessResponse();
    }
}
