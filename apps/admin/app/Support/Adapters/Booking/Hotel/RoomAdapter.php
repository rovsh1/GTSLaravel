<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Booking\Hotel;

use Module\Booking\Moderation\Application\Dto\AddRoomDto;
use Module\Booking\Moderation\Application\Dto\UpdateRoomDto;
use Module\Booking\Moderation\Application\UseCase\HotelBooking\Room\Add;
use Module\Booking\Moderation\Application\UseCase\HotelBooking\Room\Delete;
use Module\Booking\Moderation\Application\UseCase\HotelBooking\Room\GetAvailableRooms;
use Module\Booking\Moderation\Application\UseCase\HotelBooking\Room\Guest\Bind;
use Module\Booking\Moderation\Application\UseCase\HotelBooking\Room\Guest\Unbind;
use Module\Booking\Moderation\Application\UseCase\HotelBooking\Room\Update;
use Module\Booking\Pricing\Application\UseCase\HotelBooking\SetRoomManualPrice;

class RoomAdapter
{
    public function getAvailableRooms(int $bookingId): array
    {
        return app(GetAvailableRooms::class)->execute($bookingId);
    }

    public function addRoom(
        int $bookingId,
        int $roomId,
        int $rateId,
        bool $isResident,
        array|null $earlyCheckIn = null,
        array|null $lateCheckOut = null,
        ?string $note = null,
        ?int $discount = null
    ): void {
        //TODO use AddRoom name
        app(Add::class)->execute(
            new AddRoomDto(
                bookingId: $bookingId,
                roomId: $roomId,
                rateId: $rateId,
                isResident: $isResident,
                earlyCheckIn: $earlyCheckIn,
                lateCheckOut: $lateCheckOut,
                note: $note,
                discount: $discount
            )
        );
    }

    public function updateRoom(
        int $bookingId,
        int $roomBookingId,
        int $roomId,
        int $rateId,
        bool $isResident,
        array|null $earlyCheckIn = null,
        array|null $lateCheckOut = null,
        ?string $note = null,
        ?int $discount = null
    ): void {
        app(Update::class)->execute(
            new UpdateRoomDto(
                bookingId: $bookingId,
                roomBookingId: $roomBookingId,
                roomId: $roomId,
                rateId: $rateId,
                isResident: $isResident,
                earlyCheckIn: $earlyCheckIn,
                lateCheckOut: $lateCheckOut,
                note: $note,
                discount: $discount
            )
        );
    }

    public function deleteRoom(int $bookingId, int $roomBookingId): void
    {
        app(Delete::class)->execute(
            $bookingId,
            $roomBookingId
        );
    }

    public function bindRoomGuest(int $bookingId, int $roomBookingId, int $guestId): void
    {
        app(Bind::class)->execute(
            bookingId: $bookingId,
            roomBookingId: $roomBookingId,
            guestId: $guestId
        );
    }

    public function unbindRoomGuest(int $bookingId, int $roomBookingId, int $guestId): void
    {
        app(Unbind::class)->execute(
            bookingId: $bookingId,
            roomBookingId: $roomBookingId,
            guestId: $guestId
        );
    }

    public function updateRoomPrice(
        int $bookingId,
        int $roomBookingId,
        float|null $supplierDayPrice,
        float|null $clientDayPrice
    ): void {
        app(SetRoomManualPrice::class)->execute($bookingId, $roomBookingId, $supplierDayPrice, $clientDayPrice);
    }
}
