<?php

declare(strict_types=1);

namespace Module\Booking\Invoicing\Domain\Service\Factory\Details;

use Illuminate\Support\Collection;
use Module\Booking\Invoicing\Domain\Service\Dto\Booking\CarDto;
use Module\Booking\Invoicing\Domain\Service\Dto\Booking\PriceDto;
use Module\Booking\Invoicing\Domain\Service\Dto\ServiceInfoDto;
use Module\Booking\Invoicing\Domain\Service\Factory\BookingPeriodDataFactory;
use Module\Booking\Shared\Domain\Booking\Adapter\SupplierAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Repository\DetailsRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Service\Dto\DetailOptionDto;
use Sdk\Booking\Contracts\Entity\DetailsInterface;
use Sdk\Booking\ValueObject\CarBid;
use Sdk\Shared\Enum\CurrencyEnum;

class CarBidDataFactory
{
    /** @var Collection<int, CarDto>|CarDto[] */
    private Collection $carsIndexedById;

    public function __construct(
        private readonly SupplierAdapterInterface $supplierAdapter,
        private readonly DetailsRepositoryInterface $detailsRepository,
        private readonly BookingPeriodDataFactory $periodDataFactory,
    ) {}

    public function build(Booking $booking, CarBid $carBid): ServiceInfoDto
    {
        $details = $this->detailsRepository->findOrFail($booking->id());
        $cars = $this->supplierAdapter->getSupplierCars($details->serviceInfo()->supplierId());
        $this->carsIndexedById = collect($cars)->keyBy('id');

        $bookingPeriod = $this->periodDataFactory->build($details);

        return new ServiceInfoDto(
            title: $details->serviceInfo()->title(),
            bookingPeriod: $bookingPeriod,
            detailOptions: $this->buildDetails($carBid, $details),
            guests: [],//@todo гости из автомобилей,
            price: $this->buildPrice($carBid, $booking->prices()->clientPrice()->currency(), $bookingPeriod->countDays),
        );
    }

    private function buildDetails(CarBid $carBid, DetailsInterface $details): Collection
    {
        $car = $this->carsIndexedById[$carBid->carId()->value()];

        return collect([
            DetailOptionDto::createText('Марка авто', $car->mark . ' ' . $car->model),
            DetailOptionDto::createDate('Дата', $details->serviceDate()),
        ]);
    }

    private function buildPrice(CarBid $carBid, CurrencyEnum $currency, ?int $daysCount): PriceDto
    {
        $total = $daysCount === null
            ? $carBid->clientPriceValue()
            : $carBid->clientPriceValue() * $daysCount;

        return new PriceDto(
            $carBid->carsCount(),
            $carBid->clientPriceValue(),
            $total,
            $currency->name,
            null,//@todo штраф за авто?
        );
    }
}
