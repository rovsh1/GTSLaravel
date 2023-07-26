<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Booking;

use Carbon\CarbonPeriod;
use Module\Booking\HotelBooking\Application\Request\AddRoomDto;
use Module\Booking\HotelBooking\Application\Request\CreateBookingDto;
use Module\Booking\HotelBooking\Application\Request\Guest\AddRoomGuestDto;
use Module\Booking\HotelBooking\Application\Request\Guest\UpdateRoomGuestDto;
use Module\Booking\HotelBooking\Application\Request\UpdateBookingDto;
use Module\Booking\HotelBooking\Application\Request\UpdateRoomDto;
use Module\Booking\HotelBooking\Application\UseCase\Admin\BulkDeleteBookings;
use Module\Booking\HotelBooking\Application\UseCase\Admin\CreateBooking;
use Module\Booking\HotelBooking\Application\UseCase\Admin\DeleteBooking;
use Module\Booking\HotelBooking\Application\UseCase\Admin\GetBooking;
use Module\Booking\HotelBooking\Application\UseCase\Admin\GetBookingQuery;
use Module\Booking\HotelBooking\Application\UseCase\Admin\GetBookingsByFilters;
use Module\Booking\HotelBooking\Application\UseCase\Admin\UpdateBooking;
use Module\Booking\HotelBooking\Application\UseCase\Admin\UpdateExternalNumber;

class HotelAdapter
{
    public function getBookings(array $filters = []): mixed
    {
        return app(GetBookingsByFilters::class)->execute($filters);
    }

    public function getBookingQuery(): mixed
    {
        return app(GetBookingQuery::class)->execute();
    }

    public function getBooking(int $id): mixed
    {
        return app(GetBooking::class)->execute($id);
    }

    public function deleteBooking(int $id): void
    {
        app(DeleteBooking::class)->execute($id);
    }

    /**
     * @param int[] $ids
     * @return void
     */
    public function bulkDeleteBookings(array $ids): void
    {
        app(BulkDeleteBookings::class)->execute($ids);
    }

    public function createBooking(
        int $cityId,
        int $clientId,
        ?int $legalId,
        int $currencyId,
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
                legalId: $legalId,
                currencyId: $currencyId,
                hotelId: $hotelId,
                orderId: $orderId,
                period: $period,
                note: $note
            )
        );
    }

    public function updateBooking(
        int $id,
        int $clientId,
        ?int $legalId,
        int $currencyId,
        CarbonPeriod $period,
        ?string $note = null
    ): void {
        app(UpdateBooking::class)->execute(
            new UpdateBookingDto(
                id: $id,
                clientId: $clientId,
                legalId: $legalId,
                currencyId: $currencyId,
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
        //TODO use AddRoom name
        app(\Module\Booking\HotelBooking\Application\UseCase\Admin\Room\Add::class)->execute(
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
        int $roomBookingId,
        int $roomId,
        int $rateId,
        int $status,
        bool $isResident,
        array|null $earlyCheckIn = null,
        array|null $lateCheckOut = null,
        ?string $note = null,
        ?int $discount = null
    ): void {
        app(\Module\Booking\HotelBooking\Application\UseCase\Admin\Room\Update::class)->execute(
            new UpdateRoomDto(
                bookingId: $bookingId,
                roomBookingId: $roomBookingId,
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

    public function deleteRoom(int $bookingId, int $roomBookingId): void
    {
        app(\Module\Booking\HotelBooking\Application\UseCase\Admin\Room\Delete::class)->execute(
            $bookingId,
            $roomBookingId
        );
    }

    public function addRoomGuest(
        int $bookingId,
        int $roomBookingId,
        string $fullName,
        int $countryId,
        int $gender,
        bool $isAdult,
        ?int $age,
    ): void {
        app(\Module\Booking\HotelBooking\Application\UseCase\Admin\Room\Guest\Add::class)->execute(
            new AddRoomGuestDto(
                bookingId: $bookingId,
                roomBookingId: $roomBookingId,
                countryId: $countryId,
                fullName: $fullName,
                isAdult: $isAdult,
                gender: $gender,
                age: $age
            )
        );
    }

    public function updateRoomGuest(
        int $bookingId,
        int $roomBookingId,
        int $guestIndex,
        string $fullName,
        int $countryId,
        int $gender,
        bool $isAdult,
        ?int $age,
    ): void {
        app(\Module\Booking\HotelBooking\Application\UseCase\Admin\Room\Guest\Update::class)->execute(
            new UpdateRoomGuestDto(
                bookingId: $bookingId,
                roomBookingId: $roomBookingId,
                guestIndex: $guestIndex,
                countryId: $countryId,
                fullName: $fullName,
                isAdult: $isAdult,
                gender: $gender,
                age: $age
            )
        );
    }

    public function deleteRoomGuest(int $bookingId, int $roomBookingId, int $guestIndex): void
    {
        app(\Module\Booking\HotelBooking\Application\UseCase\Admin\Room\Guest\Delete::class)->execute(
            bookingId: $bookingId,
            roomBookingId: $roomBookingId,
            guestIndex: $guestIndex,
        );
    }

    public function updateExternalNumber(int $bookingId, int $type, ?string $number): void
    {
        app(UpdateExternalNumber::class)->execute($bookingId, $type, $number);
    }
}
