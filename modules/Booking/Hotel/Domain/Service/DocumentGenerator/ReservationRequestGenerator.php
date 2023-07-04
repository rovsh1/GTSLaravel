<?php

namespace Module\Booking\Hotel\Domain\Service\DocumentGenerator;

use Module\Booking\Common\Domain\Adapter\AdministratorAdapterInterface;
use Module\Booking\Common\Domain\Adapter\FileStorageAdapterInterface;
use Module\Booking\Common\Domain\Entity\AbstractBooking;
use Module\Booking\Hotel\Domain\Adapter\HotelAdapterInterface;
use Module\Booking\Hotel\Domain\Entity\Booking;
use Module\Shared\Enum\ContactTypeEnum;

class ReservationRequestGenerator extends AbstractGenerator
{
    public function __construct(
        string $templatesPath,
        FileStorageAdapterInterface $fileStorageAdapter,
        private readonly HotelAdapterInterface $hotelAdapter,
        private readonly AdministratorAdapterInterface $administratorAdapter,
    ) {
        parent::__construct($templatesPath, $fileStorageAdapter);
    }

    protected function getTemplateName(): string
    {
        return 'hotel.reservation_request';
    }

    protected function getReservationAttributes(AbstractBooking|Booking $booking): array
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

        //@todo вывести менеджера
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
            'reservStatus' => $booking->status()->name,
            'rooms' => $booking->roomBookings(),
            'hotelDefaultCheckInTime' => $booking->hotelInfo()->checkInTime()->value(),
            'hotelDefaultCheckOutTime' => $booking->hotelInfo()->checkOutTime()->value(),
        ];
    }
}
