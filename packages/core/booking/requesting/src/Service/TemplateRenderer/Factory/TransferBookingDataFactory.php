<?php

namespace Pkg\Booking\Requesting\Service\TemplateRenderer\Factory;

use Module\Booking\Shared\Domain\Booking\Adapter\SupplierAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\DbContext\CarBidDbContextInterface;
use Module\Booking\Shared\Domain\Booking\Repository\DetailsRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Service\DetailOptionsDataFactory;
use Module\Booking\Shared\Domain\Guest\Guest;
use Module\Booking\Shared\Domain\Guest\Repository\GuestRepositoryInterface;
use Pkg\Booking\Requesting\Service\TemplateRenderer\Dto\GuestDto;
use Pkg\Booking\Requesting\Service\TemplateRenderer\Dto\ServiceDto;
use Pkg\Booking\Requesting\Service\TemplateRenderer\Dto\TransferBooking\BookingPeriodDto;
use Pkg\Booking\Requesting\Service\TemplateRenderer\Dto\TransferBooking\CarDto;
use Pkg\Booking\Requesting\Service\TemplateRenderer\Dto\TransferBooking\CarPriceDto;
use Pkg\Booking\Requesting\Service\TemplateRenderer\TemplateData\TemplateDataInterface;
use Pkg\Booking\Requesting\Service\TemplateRenderer\TemplateData\TransferBooking\BookingRequest;
use Sdk\Booking\Entity\CarBid;
use Sdk\Booking\Entity\Details\CarRentWithDriver;
use Sdk\Booking\Enum\RequestTypeEnum;
use Sdk\Booking\ValueObject\BookingPeriod;
use Sdk\Booking\ValueObject\CarBidCollection;
use Sdk\Booking\ValueObject\GuestIdCollection;
use Sdk\Shared\Contracts\Adapter\CountryAdapterInterface;
use Sdk\Shared\Contracts\Service\TranslatorInterface;
use Sdk\Shared\Enum\GenderEnum;

class TransferBookingDataFactory
{
    private array $countryNamesIndexedId;

    public function __construct(
        private readonly DetailsRepositoryInterface $detailsRepository,
        private readonly DetailOptionsDataFactory $detailOptionsFactory,
        private readonly SupplierAdapterInterface $supplierAdapter,
        private readonly GuestRepositoryInterface $guestRepository,
        private readonly TranslatorInterface $translator,
        private readonly CarBidDbContextInterface $carBidDbContext,
        CountryAdapterInterface $countryAdapter,
    ) {
        $countries = $countryAdapter->get();
        $this->countryNamesIndexedId = collect($countries)->keyBy('id')->map->name->all();
    }

    public function build(Booking $booking, RequestTypeEnum $requestType): TemplateDataInterface
    {
        $bookingDetails = $this->detailsRepository->findOrFail($booking->id());

        $serviceDto = new ServiceDto(
            $bookingDetails->serviceInfo()->title(),
            $this->translator->translateEnum($bookingDetails->serviceType()),
            $bookingDetails->serviceType()
        );

        $detailOptions = $this->detailOptionsFactory->build($bookingDetails);
        $bookingPeriodDto = null;
        if ($bookingDetails instanceof CarRentWithDriver && $bookingDetails->bookingPeriod() !== null) {
            $bookingPeriodDto = $this->buildBookingPeriod($bookingDetails->bookingPeriod());
        }

        $carBids = $this->carBidDbContext->getByBookingId($booking->id());

        return match ($requestType) {
            RequestTypeEnum::BOOKING,
            RequestTypeEnum::CHANGE,
            RequestTypeEnum::CANCEL => new BookingRequest(
                $serviceDto,
                $this->buildCars(
                    $carBids,
                    $bookingDetails->serviceInfo()->supplierId(),
                    $bookingPeriodDto?->countDays
                ),
                $detailOptions,
                $bookingDetails->serviceDate(),
                $bookingPeriodDto,
            ),
        };
    }

    private function buildCars(CarBidCollection $carBids, int $supplierId, ?int $daysCount): array
    {
        $cars = $this->supplierAdapter->getSupplierCars($supplierId);
        $carsIndexedById = collect($cars)->keyBy('id');

        return $carBids->map(fn(CarBid $carBid) => new CarDto(
            $carBid->id()->value(),
            $carsIndexedById[$carBid->carId()->value()]->mark,
            $carsIndexedById[$carBid->carId()->value()]->model,
            $carBid->details()->carsCount(),
            $carBid->details()->passengersCount(),
            $carBid->details()->baggageCount(),
            $carBid->details()->babyCount(),
            new CarPriceDto(
                $carBid->prices()->supplierPrice()->currency()->name,
                $carBid->prices()->supplierPrice()->valuePerCar(),
                $carBid->supplierPriceValue(),
                $daysCount === null
                    ? $carBid->supplierPriceValue()
                    : $carBid->supplierPriceValue() * $daysCount
            ),
            $this->buildGuests($carBid->guestIds())
        ));
    }

    private function buildBookingPeriod(BookingPeriod $bookingPeriod): BookingPeriodDto
    {
        return new BookingPeriodDto(
            $bookingPeriod->dateFrom()->format('d.m.Y'),
            $bookingPeriod->dateFrom()->format('H:i'),
            $bookingPeriod->dateTo()->format('d.m.Y'),
            $bookingPeriod->dateTo()->format('H:i'),
            $bookingPeriod->daysCount(),
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
