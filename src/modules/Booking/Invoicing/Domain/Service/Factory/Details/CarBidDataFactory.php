<?php

declare(strict_types=1);

namespace Module\Booking\Invoicing\Domain\Service\Factory\Details;

use Illuminate\Support\Collection;
use Module\Booking\Invoicing\Domain\Service\Dto\Service\PriceDto;
use Module\Booking\Invoicing\Domain\Service\Dto\ServiceInfoDto;
use Module\Booking\Invoicing\Domain\Service\Factory\BookingPeriodDataFactory;
use Module\Booking\Invoicing\Domain\Service\Factory\GuestDataFactory;
use Module\Booking\Shared\Domain\Booking\Adapter\SupplierAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Repository\DetailsRepositoryInterface;
use Module\Booking\Shared\Domain\Shared\Service\DetailOptionDto;
use Module\Supplier\Moderation\Application\Dto\CarDto;
use Sdk\Booking\Contracts\Entity\DetailsInterface;
use Sdk\Booking\Entity\CarBid;
use Sdk\Shared\Enum\CurrencyEnum;

class CarBidDataFactory
{
    /** @var Collection<int, CarDto>|CarDto[] */
    private Collection $carsIndexedById;

    public function __construct(
        private readonly SupplierAdapterInterface $supplierAdapter,
        private readonly DetailsRepositoryInterface $detailsRepository,
        private readonly BookingPeriodDataFactory $periodDataFactory,
        private readonly GuestDataFactory $guestDataFactory,
    ) {}

    public function build(Booking $booking, CarBid $carBid): ServiceInfoDto
    {
        $details = $this->detailsRepository->findOrFail($booking->id());
        $cars = $this->supplierAdapter->getSupplierCars($details->serviceInfo()->supplierId());
        $service = $this->supplierAdapter->findService($details->serviceInfo()->id());
        $this->carsIndexedById = collect($cars)->keyBy('id');

        $bookingPeriod = $this->periodDataFactory->build($details);

        return new ServiceInfoDto(
            title: $service->title,
            bookingPeriod: $bookingPeriod,
            detailOptions: $this->buildDetails($carBid, $details),
            guests: $this->guestDataFactory->build($carBid->guestIds()),
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
        $valuePerCar = $carBid->prices()->clientPrice()->manualValuePerCar() ?? $carBid->prices()->clientPrice()->valuePerCar();
        if ($daysCount > 1) {
            $valuePerCar *= $daysCount;
        }
        $total = $daysCount === null
            ? $carBid->clientPriceValue()
            : $carBid->clientPriceValue() * $daysCount;

        return new PriceDto(
            $carBid->details()->carsCount(),
            $valuePerCar,
            $total,
            $currency->name,
            null,//@todo штраф за авто?
        );
    }
}
