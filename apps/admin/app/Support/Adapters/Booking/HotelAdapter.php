<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Booking;

use Carbon\CarbonPeriod;
use Module\Booking\Hotel\Application\Request\AddRoomDto;
use Module\Booking\Hotel\Application\Request\CreateBookingDto;
use Module\Booking\Hotel\Application\Request\Guest\AddRoomGuestDto;
use Module\Booking\Hotel\Application\Request\Guest\UpdateRoomGuestDto;
use Module\Booking\Hotel\Application\Request\UpdateRoomDto;
use Module\Booking\Hotel\Application\UseCase\Admin\CreateBooking;
use Module\Booking\Hotel\Application\UseCase\Admin\GetBooking;
use Module\Booking\Hotel\Application\UseCase\Admin\GetBookingsByFilters;
use Module\Booking\Hotel\Application\UseCase\Admin\Room;
use Module\Booking\Hotel\Application\UseCase\Admin\UpdateExternalNumber;

class HotelAdapter
{
    public function getBookings(array $filters = []): mixed
    {
        return app(GetBookingsByFilters::class)->execute($filters);
    }

    public function getBooking(int $id): mixed
    {
        return app(GetBooking::class)->execute($id);
    }

    public function createBooking(
        int $cityId,
        int $clientId,
        int $hotelId,
        CarbonPeriod $period,
        int $creatorId,
        ?int $orderId,
        ?string $note = null
    ): int {
        return app(CreateBooking::class)->execute(
            new CreateBookingDto(
                cityId: $cityId,
                creatorId: $creatorId,
                clientId: $clientId,
                hotelId: $hotelId,
                orderId: $orderId,
                period: $period,
                note: $note
            )
        );
    }

    public function addRoom(
        int $bookingId,
        int $roomId,
        int $rateId,
        int $status,
        bool $isResident,
        array|null $earlyCheckIn = null,
        array|null $lateCheckOut = null,
        ?string $note = null,
        ?int $discount = null
    ): void {
        app(Room\Add::class)->execute(
            new AddRoomDto(
                bookingId: $bookingId,
                roomId: $roomId,
                rateId: $rateId,
                status: $status,
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
        int $roomIndex,
        int $roomId,
        int $rateId,
        int $status,
        bool $isResident,
        array|null $earlyCheckIn = null,
        array|null $lateCheckOut = null,
        ?string $note = null,
        ?int $discount = null
    ): void {
        app(Room\Update::class)->execute(
            new UpdateRoomDto(
                bookingId: $bookingId,
                roomIndex: $roomIndex,
                roomId: $roomId,
                rateId: $rateId,
                status: $status,
                isResident: $isResident,
                earlyCheckIn: $earlyCheckIn,
                lateCheckOut: $lateCheckOut,
                note: $note,
                discount: $discount
            )
        );
    }

    public function deleteRoom(int $bookingId, int $roomIndex): void
    {
        app(Room\Delete::class)->execute($bookingId, $roomIndex);
    }

    public function addRoomGuest(
        int $bookingId,
        int $roomIndex,
        string $fullName,
        int $countryId,
        int $gender,
        bool $isAdult
    ): void {
        app(Room\Guest\Add::class)->execute(
            new AddRoomGuestDto(
                bookingId: $bookingId,
                roomIndex: $roomIndex,
                countryId: $countryId,
                fullName: $fullName,
                isAdult: $isAdult,
                gender: $gender
            )
        );
    }

    public function updateRoomGuest(
        int $bookingId,
        int $roomIndex,
        int $guestIndex,
        string $fullName,
        int $countryId,
        int $gender,
        bool $isAdult
    ): void {
        app(Room\Guest\Update::class)->execute(
            new UpdateRoomGuestDto(
                bookingId: $bookingId,
                roomIndex: $roomIndex,
                guestIndex: $guestIndex,
                countryId: $countryId,
                fullName: $fullName,
                isAdult: $isAdult,
                gender: $gender
            )
        );
    }

    public function updateExternalNumber(int $bookingId, int $type, ?string $number): void
    {
        app(UpdateExternalNumber::class)->execute($bookingId, $type, $number);
    }
}
