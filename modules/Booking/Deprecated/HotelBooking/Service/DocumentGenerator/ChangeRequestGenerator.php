<?php

namespace Module\Booking\Deprecated\HotelBooking\Service\DocumentGenerator;

use Module\Booking\Application\Admin\Shared\Factory\StatusDtoFactory;
use Module\Booking\Deprecated\HotelBooking\HotelBooking;
use Module\Booking\Domain\Booking\Adapter\HotelAdapterInterface;
use Module\Booking\Domain\Order\Repository\GuestRepositoryInterface;
use Module\Booking\Domain\Shared\Adapter\AdministratorAdapterInterface;
use Module\Booking\Domain\Shared\Adapter\CountryAdapterInterface;
use Module\Booking\Domain\Shared\Entity\BookingInterface;
use Module\Booking\Domain\Shared\Service\DocumentGenerator\AbstractRequestGenerator;
use Module\Shared\Contracts\Service\CompanyRequisitesInterface;
use Module\Shared\Enum\ContactTypeEnum;

class ChangeRequestGenerator extends AbstractRequestGenerator
{
    public function __construct(
        private readonly HotelAdapterInterface $hotelAdapter,
        private readonly AdministratorAdapterInterface $administratorAdapter,
        private readonly StatusDtoFactory $statusStorage,
        private readonly GuestRepositoryInterface $guestRepository,
        private readonly CountryAdapterInterface $countryAdapter,
        CompanyRequisitesInterface $companyRequisites
    ) {
        parent::__construct($companyRequisites);
    }

    protected function getTemplateName(): string
    {
        return 'hotel.change_request';
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
            'reservUpdatedAt' => now()->format('d.m.Y H:i:s'),//@todo правильно ли, что тут now()?
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

    public function getGuestsIndexedByRoomBooking(HotelBooking $booking): array
    {
        $guests = [];
        foreach ($booking->roomBookings() as $roomBooking) {
            $guests[$roomBooking->id()->value()] = $this->guestRepository->get($roomBooking->guestIds());
        }

        return $guests;
    }
}
