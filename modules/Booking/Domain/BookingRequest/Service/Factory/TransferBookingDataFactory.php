<?php

namespace Module\Booking\Domain\BookingRequest\Service\Factory;

use Module\Booking\Domain\Booking\Adapter\SupplierAdapterInterface;
use Module\Booking\Domain\Booking\Booking;
use Module\Booking\Domain\Booking\Entity\CarRentWithDriver;
use Module\Booking\Domain\Booking\Factory\DetailsRepositoryFactory;
use Module\Booking\Domain\Booking\ValueObject\BookingPeriod;
use Module\Booking\Domain\Booking\ValueObject\CarBid;
use Module\Booking\Domain\Booking\ValueObject\CarBidCollection;
use Module\Booking\Domain\BookingRequest\Service\Dto\ServiceDto;
use Module\Booking\Domain\BookingRequest\Service\Dto\TransferBooking\BookingPeriodDto;
use Module\Booking\Domain\BookingRequest\Service\Dto\TransferBooking\CarDto;
use Module\Booking\Domain\BookingRequest\Service\Dto\TransferBooking\CarPriceDto;
use Module\Booking\Domain\BookingRequest\Service\Factory\TransferBooking\DetailOptionsDataFactory;
use Module\Booking\Domain\BookingRequest\Service\TemplateData\TransferBooking;
use Module\Booking\Domain\BookingRequest\Service\TemplateDataInterface;
use Module\Booking\Domain\BookingRequest\ValueObject\RequestTypeEnum;
use Module\Shared\Contracts\Service\TranslatorInterface;

class TransferBookingDataFactory
{
    public function __construct(
        private readonly DetailsRepositoryFactory $detailsRepositoryFactory,
        private readonly DetailOptionsDataFactory $detailOptionsFactory,
        private readonly SupplierAdapterInterface $supplierAdapter,
        private readonly TranslatorInterface $translator,
    ) {}

    public function build(Booking $booking, RequestTypeEnum $requestType): TemplateDataInterface
    {
        $repository = $this->detailsRepositoryFactory->build($booking);
        $bookingDetails = $repository->findOrFail($booking->id());

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

        return match ($requestType) {
            RequestTypeEnum::BOOKING,
            RequestTypeEnum::CHANGE,
            RequestTypeEnum::CANCEL => new TransferBooking\BookingRequest(
                $serviceDto,
                $this->buildCars(
                    $bookingDetails->carBids(),
                    $bookingDetails->serviceInfo()->supplierId(),
                    $bookingPeriodDto?->countDays
                ),
                $detailOptions,
                $bookingPeriodDto,
            ),
        };
    }

    private function buildCars(CarBidCollection $carBids, int $supplierId, ?int $daysCount): array
    {
        $cars = $this->supplierAdapter->getSupplierCars($supplierId);
        $carsIndexedById = collect($cars)->keyBy('id');

        return $carBids->map(fn(CarBid $carBid) => new CarDto(
            $carsIndexedById[$carBid->carId()->value()]->mark,
            $carsIndexedById[$carBid->carId()->value()]->model,
            $carBid->carsCount(),
            $carBid->passengersCount(),
            $carBid->baggageCount(),
            $carBid->babyCount(),
            new CarPriceDto(
                $carBid->prices()->supplierPrice()->valuePerCar(),
                $carBid->supplierPriceValue(),
                $daysCount === null
                    ? $carBid->supplierPriceValue()
                    : $carBid->supplierPriceValue() * $daysCount
            )
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
}
