<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Booking\Hotel;

use Module\Booking\Moderation\Application\RequestDto\AddAccommodationRequestDto;
use Module\Booking\Moderation\Application\RequestDto\UpdateRoomRequestDto;
use Module\Booking\Moderation\Application\UseCase\HotelBooking\Accommodation\Add;
use Module\Booking\Moderation\Application\UseCase\HotelBooking\Accommodation\BindGuest;
use Module\Booking\Moderation\Application\UseCase\HotelBooking\Accommodation\Delete;
use Module\Booking\Moderation\Application\UseCase\HotelBooking\Accommodation\GetAvailableRooms;
use Module\Booking\Moderation\Application\UseCase\HotelBooking\Accommodation\UnbindGuest;
use Module\Booking\Moderation\Application\UseCase\HotelBooking\Accommodation\Update;
use Module\Booking\Pricing\Application\UseCase\HotelBooking\SetRoomManualPrice;

class AccommodationAdapter
{
    public function getAvailableRooms(int $bookingId): array
    {
        return app(GetAvailableRooms::class)->execute($bookingId);
    }

    public function add(
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
            new AddAccommodationRequestDto(
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

    public function update(
        int $bookingId,
        int $accommodationId,
        int $roomId,
        int $rateId,
        bool $isResident,
        array|null $earlyCheckIn = null,
        array|null $lateCheckOut = null,
        ?string $note = null,
        ?int $discount = null
    ): void {
        app(Update::class)->execute(
            new UpdateRoomRequestDto(
                bookingId: $bookingId,
                accommodationId: $accommodationId,
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

    public function delete(int $bookingId, int $accommodationId): void
    {
        app(Delete::class)->execute(
            $bookingId,
            $accommodationId
        );
    }

    public function bindGuest(int $bookingId, int $accommodationId, int $guestId): void
    {
        app(BindGuest::class)->execute(
            bookingId: $bookingId,
            accommodationId: $accommodationId,
            guestId: $guestId
        );
    }

    public function unbindGuest(int $bookingId, int $accommodationId, int $guestId): void
    {
        app(UnbindGuest::class)->execute(
            bookingId: $bookingId,
            accommodationId: $accommodationId,
            guestId: $guestId
        );
    }

    public function updatePrice(
        int $bookingId,
        int $accommodationId,
        float|null $supplierDayPrice,
        float|null $clientDayPrice
    ): void {
        app(SetRoomManualPrice::class)->execute($bookingId, $accommodationId, $supplierDayPrice, $clientDayPrice);
    }
}
