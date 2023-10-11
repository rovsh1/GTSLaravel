<?php

namespace Module\Support\MailManager\Infrastructure\Service\DataBuilder\Support;

use Module\Booking\Application\Admin\HotelBooking\Dto\BookingDto;
use Module\Booking\Application\Admin\HotelBooking\Dto\Details\RoomBookingDto;
use Module\Booking\HotelBooking\Application\Dto\Details\RoomBooking\GuestDto;
use Module\Support\MailManager\Domain\Service\DataBuilder\DataDto\HotelBooking\BookingInfoDto;
use Module\Support\MailManager\Domain\Service\DataBuilder\DataDto\HotelBooking\BookingRoomDto;

abstract class HotelBookingDtoFactory
{
    public static function makeBookingInfo(BookingDto $bookingDto): BookingInfoDto
    {
        return new BookingInfoDto(
            id: $bookingDto->id,
            url: '',
            dateCheckin: $bookingDto->period->dateFrom,
            dateCheckout: $bookingDto->period->dateTo,
            nightsNumber: $bookingDto->period->nightsCount,
            priceNet: $bookingDto->prices->netValue,
            status: $bookingDto->status->name,
            note: $bookingDto->note,
            createdAt: $bookingDto->createdAt
        );
    }

    public static function makeRoomsArray(BookingDto $bookingDto): array
    {
        return array_map(fn(RoomBookingDto $roomDto) => new BookingRoomDto(
            roomId: $roomDto->roomInfo->id,
            name: $roomDto->roomInfo->name,
            checkinTime: null,
            checkoutTime: null,
            guestsNames: implode(', ', array_map(fn(GuestDto $g) => $g->fullName, $roomDto->guests)),
            priceNet: $roomDto->price->netValue,
            guestsNumber: count($roomDto->guests),
            note: $roomDto->details->guestNote
        ), $bookingDto->roomBookings);
    }
}
