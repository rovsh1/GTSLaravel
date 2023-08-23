<?php

namespace Module\Booking\HotelBooking\Domain\Service\DocumentGenerator;

use Module\Booking\Common\Application\Service\StatusStorage;
use Module\Booking\Common\Domain\Adapter\AdministratorAdapterInterface;
use Module\Booking\Common\Domain\Adapter\FileStorageAdapterInterface;
use Module\Booking\Common\Domain\Entity\BookingInterface;
use Module\Booking\Common\Domain\Service\DocumentGenerator\AbstractRequestGenerator;
use Module\Booking\HotelBooking\Domain\Adapter\HotelAdapterInterface;
use Module\Booking\HotelBooking\Domain\Entity\Booking;
use Module\Shared\Enum\ContactTypeEnum;

class ReservationRequestGenerator extends AbstractRequestGenerator
{
    public function __construct(
        FileStorageAdapterInterface $fileStorageAdapter,
        private readonly HotelAdapterInterface $hotelAdapter,
        private readonly AdministratorAdapterInterface $administratorAdapter,
        private readonly StatusStorage $statusStorage,
    ) {
        parent::__construct($fileStorageAdapter);
    }

    protected function getTemplateName(): string
    {
        return 'hotel.reservation_request';
    }

    protected function getBookingAttributes(BookingInterface|Booking $booking): array
    {
        $hotelDto = $this->hotelAdapter->findById($booking->hotelInfo()->id());
        $phones = collect($hotelDto->contacts)
            ->map(function (mixed $contactDto) {
                if ($contactDto->type === ContactTypeEnum::PHONE->value) {
                    return $contactDto->value;
                }

                return null;
            })
            ->filter()
            ->implode(', ');

        $administrator = $this->administratorAdapter->getManagerByBookingId($booking->id()->value());

        //@todo инфо о гостях сейчас в айдишниках

        return [
            'reservCreatedAt' => $booking->createdAt()->format('d.m.Y H:i:s'),
            'hotelName' => $booking->hotelInfo()->name(),
            'hotelPhone' => $phones,
            'cityName' => $hotelDto->cityName,
            'reservStartDate' => $booking->period()->dateFrom()->format('d.m.Y'),
            'reservEndDate' => $booking->period()->dateTo()->format('d.m.Y'),
            'reservNightCount' => $booking->period()->nightsCount(),
            'reservNumber' => $booking->id()->value(),
            'reservStatus' => $this->statusStorage->get($booking->status())->name,
            'rooms' => $booking->roomBookings(),
            'hotelDefaultCheckInTime' => $booking->hotelInfo()->checkInTime()->value(),
            'hotelDefaultCheckOutTime' => $booking->hotelInfo()->checkOutTime()->value(),
            'managerName' => $administrator?->name ?? $administrator?->presentation,//@todo надо ли?
            'managerPhone' => $administrator?->phone,
            'managerEmail' => $administrator?->email,
        ];
    }
}
