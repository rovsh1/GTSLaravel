<?php

namespace Pkg\Booking\Requesting\Service\TemplateRenderer\Factory;

use Module\Booking\Moderation\Application\Exception\HotelDetailsExpectedException;
use Module\Booking\Shared\Domain\Booking\Adapter\HotelAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Repository\AccommodationRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\DetailsRepositoryInterface;
use Module\Booking\Shared\Domain\Guest\Guest;
use Module\Booking\Shared\Domain\Guest\Repository\GuestRepositoryInterface;
use Module\Hotel\Moderation\Application\Dto\ContactDto;
use Pkg\Booking\Requesting\Service\TemplateRenderer\Dto\BookingPriceDto;
use Pkg\Booking\Requesting\Service\TemplateRenderer\Dto\GuestDto;
use Pkg\Booking\Requesting\Service\TemplateRenderer\Dto\HotelBooking\BookingPeriodDto;
use Pkg\Booking\Requesting\Service\TemplateRenderer\Dto\HotelBooking\HotelInfoDto;
use Pkg\Booking\Requesting\Service\TemplateRenderer\Dto\HotelBooking\RoomDto;
use Pkg\Booking\Requesting\Service\TemplateRenderer\TemplateData\HotelBooking\BookingRequest;
use Pkg\Booking\Requesting\Service\TemplateRenderer\TemplateData\TemplateDataInterface;
use Sdk\Booking\Entity\Details\HotelBooking;
use Sdk\Booking\Entity\HotelAccommodation;
use Sdk\Booking\Enum\RequestTypeEnum;
use Sdk\Booking\ValueObject\GuestIdCollection;
use Sdk\Booking\ValueObject\HotelBooking\AccommodationCollection;
use Sdk\Shared\Contracts\Adapter\CountryAdapterInterface;
use Sdk\Shared\Enum\ContactTypeEnum;
use Sdk\Shared\Enum\GenderEnum;

class HotelBookingDataFactory
{

    private array $countryNamesIndexedId;

    public function __construct(
        private readonly DetailsRepositoryInterface $detailsRepository,
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
        $bookingDetails = $this->detailsRepository->findOrFail($booking->id());
        if (!$bookingDetails instanceof HotelBooking) {
            throw new HotelDetailsExpectedException();
        }

        $accommodations = $this->accommodationRepository->getByBookingId($booking->id());
        $roomsDto = $this->buildRoomsDto($accommodations, $bookingDetails);
        $hotelInfoDto = $this->buildHotelInfo($bookingDetails);
        $bookingPeriodDto = $this->buildPeriodDto($bookingDetails);

        return match ($requestType) {
            RequestTypeEnum::BOOKING,
            RequestTypeEnum::CHANGE,
            RequestTypeEnum::CANCEL => new BookingRequest($roomsDto, $hotelInfoDto, $bookingPeriodDto),
        };
    }

    private function buildHotelInfo(HotelBooking $bookingDetails): HotelInfoDto
    {
        $hotelDto = $this->hotelAdapter->findById($bookingDetails->hotelInfo()->id());

        return new HotelInfoDto(
            $bookingDetails->hotelInfo()->id(),
            $bookingDetails->hotelInfo()->name(),
            $this->buildHotelPhones($hotelDto->contacts),
            $hotelDto->cityName,
        );
    }

    private function buildPeriodDto(HotelBooking $bookingDetails): BookingPeriodDto
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
     * @param HotelBooking $bookingDetails
     * @return RoomDto[]
     */
    private function buildRoomsDto(AccommodationCollection $accommodations, HotelBooking $bookingDetails): array
    {
        if ($accommodations->count() === 0) {
            return [];
        }
        $hotelPriceRates = $this->hotelAdapter->getHotelRates($bookingDetails->hotelInfo()->id());
        $hotelPriceRatesIndexedId = collect($hotelPriceRates)->keyBy('id');

        return $accommodations->map(
            function (HotelAccommodation $accommodation) use ($bookingDetails, $hotelPriceRatesIndexedId) {
                $checkInTime = $bookingDetails->hotelInfo()->checkInTime()->value();
                $earlyCheckIn = $accommodation->details()->earlyCheckIn();
                if ($earlyCheckIn !== null) {
                    $checkInTime = $earlyCheckIn->timePeriod()->from() . " (+{$earlyCheckIn->priceMarkup()->value()}%)";
                }
                $checkOutTime = $bookingDetails->hotelInfo()->checkOutTime()->value();
                $lateCheckOut = $accommodation->details()->lateCheckOut();
                if ($lateCheckOut !== null) {
                    $checkOutTime = $lateCheckOut->timePeriod()->to() . " (+{$lateCheckOut->priceMarkup()->value()}%)";
                }

                return new RoomDto(
                    $accommodation->id()->value(),
                    $accommodation->roomInfo()->name(),
                    $hotelPriceRatesIndexedId[$accommodation->details()->rateId()]->name,
                    $checkInTime,
                    $checkOutTime,
                    $this->buildGuests($accommodation->guestIds()),
                    $accommodation->details()->guestNote(),
                    new BookingPriceDto(
                        $accommodation->prices()->supplierPrice()->manualValue() ?? $accommodation->prices()->supplierPrice()->value(),
                        'UZS',//@todo валюта поставщика
                    ),
                    new BookingPriceDto(
                        $accommodation->prices()->clientPrice()->manualValue() ?? $accommodation->prices()->clientPrice()->value(),
                        'UZS',
                    ),
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
