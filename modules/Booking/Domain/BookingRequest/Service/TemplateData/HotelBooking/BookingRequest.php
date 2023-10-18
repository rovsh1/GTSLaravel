<?php

namespace Module\Booking\Domain\BookingRequest\Service\TemplateData\HotelBooking;

use Module\Booking\Domain\BookingRequest\Service\TemplateDataInterface;

final class BookingRequest implements TemplateDataInterface
{
    public function toArray(): array
    {
        return [
            'booking' => $bookingDto,
            'rooms' => $roomsDto,
            'manager' => $managerDto
//            'reservCreatedAt' => $booking->createdAt()->format('d.m.Y H:i:s'),
//            'hotelName' => $booking->hotelInfo()->name(),
//            'hotelPhone' => $phones,
//            'cityName' => $hotelDto->cityName,
//            'reservStartDate' => $booking->period()->dateFrom()->format('d.m.Y'),
//            'reservEndDate' => $booking->period()->dateTo()->format('d.m.Y'),
//            'reservNightCount' => $booking->period()->nightsCount(),
//            'reservNumber' => $booking->id()->value(),
//            'reservStatus' => $this->statusStorage->get($booking->status())->name,
//            'rooms' => $booking->roomBookings(),
//            'roomsGuests' => $guests,
//            'countryNamesById' => $countries,
//            'hotelDefaultCheckInTime' => $booking->hotelInfo()->checkInTime()->value(),
//            'hotelDefaultCheckOutTime' => $booking->hotelInfo()->checkOutTime()->value(),
//            'managerName' => $administrator?->name ?? $administrator?->presentation,//@todo надо ли?
//            'managerPhone' => $administrator?->phone,
//            'managerEmail' => $administrator?->email,
        ];
    }
}