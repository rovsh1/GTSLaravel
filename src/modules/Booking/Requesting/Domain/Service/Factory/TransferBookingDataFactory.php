<?php

namespace Module\Booking\Requesting\Domain\Service\Factory;

use Module\Booking\Requesting\Domain\Service\Dto\ServiceDto;
use Module\Booking\Requesting\Domain\Service\Dto\TransferBooking\BookingPeriodDto;
use Module\Booking\Requesting\Domain\Service\Dto\TransferBooking\CarDto;
use Module\Booking\Requesting\Domain\Service\Dto\TransferBooking\CarPriceDto;
use Module\Booking\Requesting\Domain\Service\TemplateData\TransferBooking\BookingRequest;
use Module\Booking\Requesting\Domain\Service\TemplateDataInterface;
use Module\Booking\Shared\Domain\Booking\Adapter\SupplierAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\DbContext\CarBidDbContextInterface;
use Module\Booking\Shared\Domain\Booking\Repository\DetailsRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Service\DetailOptionsDataFactory;
use Sdk\Booking\Entity\CarBid;
use Sdk\Booking\Entity\Details\CarRentWithDriver;
use Sdk\Booking\Enum\RequestTypeEnum;
use Sdk\Booking\ValueObject\BookingPeriod;
use Sdk\Booking\ValueObject\CarBidCollection;
use Sdk\Shared\Contracts\Service\TranslatorInterface;

class TransferBookingDataFactory
{
    public function __construct(
        private readonly DetailsRepositoryInterface $detailsRepository,
        private readonly DetailOptionsDataFactory $detailOptionsFactory,
        private readonly SupplierAdapterInterface $supplierAdapter,
        private readonly TranslatorInterface $translator,
        private readonly CarBidDbContextInterface $carBidDbContext,
    ) {}

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
