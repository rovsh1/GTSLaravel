<?php

namespace Module\Booking\Domain\BookingRequest\Service\Factory;

use Module\Booking\Domain\Booking\Adapter\HotelAdapterInterface;
use Module\Booking\Domain\Booking\Booking;
use Module\Booking\Domain\Booking\Entity\HotelBooking as DetailsEntity;
use Module\Booking\Domain\Booking\Repository\Details\HotelBookingRepositoryInterface;
use Module\Booking\Domain\Booking\Repository\RoomBookingRepositoryInterface;
use Module\Booking\Domain\BookingRequest\Service\Dto\HotelBooking\BookingPeriodDto;
use Module\Booking\Domain\BookingRequest\Service\Dto\HotelBooking\GuestDto;
use Module\Booking\Domain\BookingRequest\Service\Dto\HotelBooking\HotelInfoDto;
use Module\Booking\Domain\BookingRequest\Service\TemplateData\AirportBooking;
use Module\Booking\Domain\BookingRequest\Service\TemplateDataInterface;
use Module\Booking\Domain\BookingRequest\ValueObject\RequestTypeEnum;
use Module\Booking\Domain\Order\Entity\Guest;
use Module\Booking\Domain\Order\Repository\GuestRepositoryInterface;
use Module\Booking\Domain\Shared\Adapter\CountryAdapterInterface;
use Module\Booking\Domain\Shared\ValueObject\GuestIdCollection;
use Module\Shared\Enum\GenderEnum;

class AirportBookingDataFactory
{

    private array $countryNamesIndexedId;

    public function __construct(
        private readonly HotelBookingRepositoryInterface $hotelBookingRepository,
        private readonly RoomBookingRepositoryInterface $roomBookingRepository,
        private readonly HotelAdapterInterface $hotelAdapter,
        private readonly GuestRepositoryInterface $guestRepository,
        CountryAdapterInterface $countryAdapter,
    ) {
        $countries = $countryAdapter->get();
        $this->countryNamesIndexedId = collect($countries)->keyBy('id')->map->name->all();
    }

    public function build(Booking $booking, RequestTypeEnum $requestType): TemplateDataInterface
    {
        $bookingDetails = $this->hotelBookingRepository->findOrFail($booking->id());
        $rooms = $this->roomBookingRepository->get($bookingDetails->roomBookings());
        $roomsDto = $this->buildRoomsDto($rooms, $bookingDetails);
        $detailsData = $this->buildDetailsData($booking, $bookingDetails);
        $detailsData = [
            'rooms' => $roomsDto,
            ...$detailsData
        ];

        return match ($requestType) {
            RequestTypeEnum::BOOKING => new AirportBooking\BookingRequest($detailsData),
            RequestTypeEnum::CHANGE => new AirportBooking\BookingRequest($detailsData),
            RequestTypeEnum::CANCEL => new AirportBooking\BookingRequest($detailsData),
        };
    }

    private function buildDetailsData(Booking $booking, DetailsEntity $bookingDetails): array
    {
        $hotelDto = $this->hotelAdapter->findById($bookingDetails->hotelInfo()->id());

        return [
            'hotel' => new HotelInfoDto(
                $bookingDetails->hotelInfo()->id(),
                $bookingDetails->hotelInfo()->name(),
                $this->buildHotelPhones($hotelDto->contacts),
                $hotelDto->cityName,
            ),
            'bookingPeriod' => new BookingPeriodDto(
                $bookingDetails->bookingPeriod()->dateFrom()->format('d.m.Y H:i:s'),
                $bookingDetails->bookingPeriod()->dateTo()->format('d.m.Y H:i:s'),
                $bookingDetails->bookingPeriod()->nightsCount()
            ),
        ];
    }

    /**
     * @param GuestIdCollection $guestIds
     * @return GuestDto[]
     */
    private function buildGuests(GuestIdCollection $guestIds): array
    {
        if ($guestIds->count() === 0) {
            return [];
        }
        $guests = $this->guestRepository->get($guestIds);

        return collect($guests)->map(fn(Guest $guest) => new GuestDto(
            $guest->fullName(),
            $guest->gender() === GenderEnum::MALE ? 'Мужской' : 'Женский',
            $this->countryNamesIndexedId[$guest->countryId()]
        ))->all();
    }
}
