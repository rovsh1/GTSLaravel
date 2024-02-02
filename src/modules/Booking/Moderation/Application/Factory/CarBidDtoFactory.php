<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Factory;

use Module\Booking\Moderation\Application\Dto\ServiceBooking\CarBidDto;
use Module\Booking\Moderation\Application\Dto\ServiceBooking\CarBidPriceItemDto;
use Module\Booking\Moderation\Application\Dto\ServiceBooking\CarBidPricesDto;
use Module\Booking\Shared\Domain\Booking\Adapter\SupplierAdapterInterface;
use Module\Booking\Shared\Domain\Booking\DbContext\CarBidDbContextInterface;
use Module\Supplier\Moderation\Application\Dto\CarDto;
use Sdk\Booking\Contracts\Entity\TransferDetailsInterface;
use Sdk\Booking\Entity\CarBid;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Shared\Contracts\Service\TranslatorInterface;
use Sdk\Shared\Dto\CurrencyDto;

class CarBidDtoFactory
{
    /**
     * @var array<int, array<int, CarDto>> $supplierCarsIndexedById
     */
    private array $supplierCarsIndexedById = [];

    public function __construct(
        private readonly SupplierAdapterInterface $supplierAdapter,
        private readonly CarBidDbContextInterface $carBidDbContext,
        private readonly TranslatorInterface $translator
    ) {}

    /**
     * @param BookingId $bookingId
     * @param int $supplierId
     * @return array<int, CarBidDto>
     */
    public function build(TransferDetailsInterface $details): array
    {
        $carBids = $this->carBidDbContext->getByBookingId($details->bookingId());

        return $carBids->map(fn(CarBid $carBid) => new CarBidDto(
            id: $carBid->id()->value(),
            carInfo: $this->getSupplierCar($details->serviceInfo()->supplierId(), $carBid->carId()->value()),
            prices: $this->buildPricesDto($carBid),
            carsCount: $carBid->details()->carsCount(),
            passengersCount: $carBid->details()->passengersCount(),
            baggageCount: $carBid->details()->baggageCount(),
            babyCount: $carBid->details()->babyCount(),
            guestIds: $carBid->guestIds()->serialize()
        ));
    }

    private function buildPricesDto(CarBid $carBid): CarBidPricesDto
    {
        $supplierPrice = $carBid->prices()->supplierPrice();
        $clientPrice = $carBid->prices()->clientPrice();

        return new CarBidPricesDto(
            supplierPrice: new CarBidPriceItemDto(
                currency: CurrencyDto::fromEnum($supplierPrice->currency(), $this->translator),
                valuePerCar: $supplierPrice->valuePerCar(),
                manualValuePerCar: $supplierPrice->manualValuePerCar()
            ),
            clientPrice: new CarBidPriceItemDto(
                currency: CurrencyDto::fromEnum($clientPrice->currency(), $this->translator),
                valuePerCar: $clientPrice->valuePerCar(),
                manualValuePerCar: $clientPrice->manualValuePerCar()
            ),
        );
    }

    private function getSupplierCar(int $supplierId, int $carId): CarDto
    {
        $supplierCars = $this->supplierCarsIndexedById[$supplierId] ?? null;
        if ($supplierCars === null) {
            $cars = $this->supplierAdapter->getSupplierCars($supplierId);
            $supplierCars = collect($cars)->keyBy('id')->all();
            $this->supplierCarsIndexedById[$supplierId] = $supplierCars;
        }

        return $supplierCars[$carId];
    }
}
