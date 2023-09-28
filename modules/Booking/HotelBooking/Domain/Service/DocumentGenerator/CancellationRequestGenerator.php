<?php

namespace Module\Booking\HotelBooking\Domain\Service\DocumentGenerator;

use Module\Booking\Common\Application\Service\StatusStorage;
use Module\Booking\Common\Domain\Adapter\AdministratorAdapterInterface;
use Module\Booking\Common\Domain\Adapter\CountryAdapterInterface;
use Module\Booking\Common\Domain\Entity\BookingInterface;
use Module\Booking\Common\Domain\Service\DocumentGenerator\AbstractRequestGenerator;
use Module\Booking\HotelBooking\Domain\Adapter\HotelAdapterInterface;
use Module\Booking\HotelBooking\Domain\Entity\Booking;
use Module\Booking\Order\Domain\Repository\GuestRepositoryInterface;
use Module\Shared\Domain\Service\CompanyRequisitesInterface;
use Module\Shared\Enum\ContactTypeEnum;

class CancellationRequestGenerator extends AbstractRequestGenerator
{
    public function __construct(
        private readonly HotelAdapterInterface $hotelAdapter,
        private readonly AdministratorAdapterInterface $administratorAdapter,
        private readonly StatusStorage $statusStorage,
        private readonly GuestRepositoryInterface $guestRepository,
        private readonly CountryAdapterInterface $countryAdapter,
        CompanyRequisitesInterface $companyRequisites
    ) {
        parent::__construct($companyRequisites);
    }

    protected function getTemplateName(): string
    {
        return 'hotel.cancellation_request';
    }

    protected function getBookingAttributes(BookingInterface $booking): array
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
        $guests = $this->getGuestsIndexedByRoomBooking($booking);
        $countries = $this->countryAdapter->get();
        $countries = collect($countries)->keyBy('id')->map->name;

        return [
            'reservCreatedAt' => $booking->createdAt()->format('d.m.Y H:i:s'),
            'reservCancelledAt' => now()->format('d.m.Y H:i:s'),//@todo правильно ли, что тут now()?
            'hotelName' => $booking->hotelInfo()->name(),
            'hotelPhone' => $phones,
            'cityName' => $hotelDto->cityName,
            'reservStartDate' => $booking->period()->dateFrom()->format('d.m.Y'),
            'reservEndDate' => $booking->period()->dateTo()->format('d.m.Y'),
            'reservNightCount' => $booking->period()->nightsCount(),
            'reservNumber' => $booking->id()->value(),
            'reservStatus' => $this->statusStorage->get($booking->status())->name,
            'rooms' => $booking->roomBookings(),
            'roomsGuests' => $guests,
            'countryNamesById' => $countries,
            'hotelDefaultCheckInTime' => $booking->hotelInfo()->checkInTime()->value(),
            'hotelDefaultCheckOutTime' => $booking->hotelInfo()->checkOutTime()->value(),
            'managerName' => $administrator?->name ?? $administrator?->presentation,//@todo надо ли?
            'managerPhone' => $administrator?->phone,
            'managerEmail' => $administrator?->email,
        ];
    }

    public function getGuestsIndexedByRoomBooking(Booking $booking): array
    {
        $guests = [];
        foreach ($booking->roomBookings() as $roomBooking) {
            $guests[$roomBooking->id()->value()] = $this->guestRepository->get($roomBooking->guestIds());
        }

        return $guests;
    }
}
