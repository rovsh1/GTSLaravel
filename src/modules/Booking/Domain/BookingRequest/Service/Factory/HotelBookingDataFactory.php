<?php

namespace Module\Booking\Domain\BookingRequest\Service\Factory;

use Module\Booking\Domain\Booking\Adapter\HotelAdapterInterface;
use Module\Booking\Domain\Booking\Booking;
use Module\Booking\Domain\Booking\Entity\HotelBooking as DetailsEntity;
use Module\Booking\Domain\Booking\Entity\HotelRoomBooking;
use Module\Booking\Domain\Booking\Repository\Details\HotelBookingRepositoryInterface;
use Module\Booking\Domain\Booking\Repository\RoomBookingRepositoryInterface;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\RoomBookingCollection;
use Module\Booking\Domain\BookingRequest\Service\Dto\GuestDto;
use Module\Booking\Domain\BookingRequest\Service\Dto\HotelBooking\BookingPeriodDto;
use Module\Booking\Domain\BookingRequest\Service\Dto\HotelBooking\HotelInfoDto;
use Module\Booking\Domain\BookingRequest\Service\Dto\HotelBooking\RoomDto;
use Module\Booking\Domain\BookingRequest\Service\TemplateData\HotelBooking;
use Module\Booking\Domain\BookingRequest\Service\TemplateDataInterface;
use Module\Booking\Domain\BookingRequest\ValueObject\RequestTypeEnum;
use Module\Booking\Domain\Guest\Guest;
use Module\Booking\Domain\Guest\Repository\GuestRepositoryInterface;
use Module\Booking\Domain\Shared\ValueObject\GuestIdCollection;
use Module\Catalog\Application\Admin\ResponseDto\ContactDto;
use Module\Shared\Contracts\Adapter\CountryAdapterInterface;
use Module\Shared\Enum\ContactTypeEnum;
use Module\Shared\Enum\GenderEnum;

class HotelBookingDataFactory
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
        $hotelInfoDto = $this->buildHotelInfo($bookingDetails);
        $bookingPeriodDto = $this->buildPeriodDto($bookingDetails);

        return match ($requestType) {
            RequestTypeEnum::BOOKING,
            RequestTypeEnum::CHANGE,
            RequestTypeEnum::CANCEL => new HotelBooking\BookingRequest($roomsDto, $hotelInfoDto, $bookingPeriodDto),
        };
    }

    private function buildHotelInfo(DetailsEntity $bookingDetails): HotelInfoDto
    {
        $hotelDto = $this->hotelAdapter->findById($bookingDetails->hotelInfo()->id());

        return new HotelInfoDto(
            $bookingDetails->hotelInfo()->id(),
            $bookingDetails->hotelInfo()->name(),
            $this->buildHotelPhones($hotelDto->contacts),
            $hotelDto->cityName,
        );
    }

    private function buildPeriodDto(DetailsEntity $bookingDetails): BookingPeriodDto
    {
        return new BookingPeriodDto(
            $bookingDetails->bookingPeriod()->dateFrom()->format('d.m.Y'),
            $bookingDetails->bookingPeriod()->dateTo()->format('d.m.Y'),
            $bookingDetails->bookingPeriod()->nightsCount()
        );
    }

    private function buildHotelPhones(array $contacts): string
    {
        return collect($contacts)
            ->map(function (ContactDto $contactDto) {
                if ($contactDto->type === ContactTypeEnum::PHONE->value) {
                    return $contactDto->value;
                }

                return null;
            })
            ->filter()
            ->implode(', ');
    }

    /**
     * @param RoomBookingCollection $roomBookings
     * @param DetailsEntity $bookingDetails
     * @return RoomDto[]
     */
    private function buildRoomsDto(RoomBookingCollection $roomBookings, DetailsEntity $bookingDetails): array
    {
        if ($roomBookings->count() === 0) {
            return [];
        }
        $hotelPriceRates = $this->hotelAdapter->getHotelRates($bookingDetails->hotelInfo()->id());
        $hotelPriceRatesIndexedId = collect($hotelPriceRates)->keyBy('id');

        return $roomBookings->map(
            function (HotelRoomBooking $roomBooking) use ($bookingDetails, $hotelPriceRatesIndexedId) {
                $checkInTime = $bookingDetails->hotelInfo()->checkInTime()->value();
                if ($roomBooking->details()->earlyCheckIn() !== null) {
                    $checkInTime = $roomBooking->details()->earlyCheckIn()->timePeriod()->from();
                }
                $checkOutTime = $bookingDetails->hotelInfo()->checkOutTime()->value();
                if ($roomBooking->details()->lateCheckOut() !== null) {
                    $checkOutTime = $roomBooking->details()->lateCheckOut()->timePeriod()->to();
                }

                return new RoomDto(
                    $roomBooking->roomInfo()->name(),
                    $hotelPriceRatesIndexedId[$roomBooking->details()->rateId()]->name,
                    $checkInTime,
                    $checkOutTime,
                    $this->buildGuests($roomBooking->guestIds()),
                    $roomBooking->details()->guestNote(),
                );
            }
        );
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
