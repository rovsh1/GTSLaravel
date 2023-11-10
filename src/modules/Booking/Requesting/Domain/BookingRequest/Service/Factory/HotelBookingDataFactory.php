<?php

namespace Module\Booking\Requesting\Domain\BookingRequest\Service\Factory;

use Module\Booking\Requesting\Domain\BookingRequest\Service\Dto\GuestDto;
use Module\Booking\Requesting\Domain\BookingRequest\Service\Dto\HotelBooking\BookingPeriodDto;
use Module\Booking\Requesting\Domain\BookingRequest\Service\Dto\HotelBooking\HotelInfoDto;
use Module\Booking\Requesting\Domain\BookingRequest\Service\Dto\HotelBooking\RoomDto;
use Module\Booking\Requesting\Domain\BookingRequest\Service\TemplateDataInterface;
use Module\Booking\Requesting\Domain\BookingRequest\ValueObject\RequestTypeEnum;
use Module\Booking\Shared\Domain\Booking\Adapter\HotelAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Entity\HotelBooking as DetailsEntity;
use Module\Booking\Shared\Domain\Booking\Entity\HotelAccommodation;
use Module\Booking\Shared\Domain\Booking\Repository\Details\HotelBookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\AccommodationRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\AccommodationCollection;
use Module\Booking\Shared\Domain\Guest\Guest;
use Module\Booking\Shared\Domain\Guest\Repository\GuestRepositoryInterface;
use Module\Booking\Shared\Domain\Shared\ValueObject\GuestIdCollection;
use Module\Hotel\Moderation\Application\Dto\ContactDto;
use Module\Shared\Contracts\Adapter\CountryAdapterInterface;
use Module\Shared\Enum\ContactTypeEnum;
use Module\Shared\Enum\GenderEnum;

class HotelBookingDataFactory
{

    private array $countryNamesIndexedId;

    public function __construct(
        private readonly HotelBookingRepositoryInterface $hotelBookingRepository,
        private readonly AccommodationRepositoryInterface $accommodationRepository,
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
        $rooms = $this->accommodationRepository->get($bookingDetails->accommodations());
        $roomsDto = $this->buildRoomsDto($rooms, $bookingDetails);
        $hotelInfoDto = $this->buildHotelInfo($bookingDetails);
        $bookingPeriodDto = $this->buildPeriodDto($bookingDetails);

        return match ($requestType) {
            RequestTypeEnum::BOOKING,
            RequestTypeEnum::CHANGE,
            RequestTypeEnum::CANCEL => new \Module\Booking\Requesting\Domain\BookingRequest\Service\TemplateData\HotelBooking\BookingRequest($roomsDto, $hotelInfoDto, $bookingPeriodDto),
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
     * @param AccommodationCollection $accommodations
     * @param DetailsEntity $bookingDetails
     * @return RoomDto[]
     */
    private function buildRoomsDto(AccommodationCollection $accommodations, DetailsEntity $bookingDetails): array
    {
        if ($accommodations->count() === 0) {
            return [];
        }
        $hotelPriceRates = $this->hotelAdapter->getHotelRates($bookingDetails->hotelInfo()->id());
        $hotelPriceRatesIndexedId = collect($hotelPriceRates)->keyBy('id');

        return $accommodations->map(
            function (HotelAccommodation $accommodation) use ($bookingDetails, $hotelPriceRatesIndexedId) {
                $checkInTime = $bookingDetails->hotelInfo()->checkInTime()->value();
                if ($accommodation->details()->earlyCheckIn() !== null) {
                    $checkInTime = $accommodation->details()->earlyCheckIn()->timePeriod()->from();
                }
                $checkOutTime = $bookingDetails->hotelInfo()->checkOutTime()->value();
                if ($accommodation->details()->lateCheckOut() !== null) {
                    $checkOutTime = $accommodation->details()->lateCheckOut()->timePeriod()->to();
                }

                return new RoomDto(
                    $accommodation->roomInfo()->name(),
                    $hotelPriceRatesIndexedId[$accommodation->details()->rateId()]->name,
                    $checkInTime,
                    $checkOutTime,
                    $this->buildGuests($accommodation->guestIds()),
                    $accommodation->details()->guestNote(),
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
