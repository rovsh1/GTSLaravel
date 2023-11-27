<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Domain\Booking\Service\TransferBooking;

use DateTimeInterface;
use Module\Booking\Moderation\Application\Dto\CarBidDataDto;
use Module\Booking\Moderation\Domain\Booking\Event\CarBidAdded;
use Module\Booking\Moderation\Domain\Booking\Event\CarBidRemoved;
use Module\Booking\Moderation\Domain\Booking\Event\CarBidUpdated;
use Module\Booking\Moderation\Domain\Booking\Factory\TransferCancelConditionsFactory;
use Module\Booking\Shared\Domain\Booking\Adapter\SupplierAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Exception\NotFoundTransferServicePrice;
use Module\Booking\Shared\Domain\Booking\Exception\ServiceDateUndefined;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\DetailsRepositoryInterface;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\CarBid;
use Sdk\Booking\ValueObject\CarBid\CarBidPriceItem;
use Sdk\Booking\ValueObject\CarBid\CarBidPrices;
use Sdk\Booking\ValueObject\CarId;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;
use Sdk\Shared\Enum\CurrencyEnum;

class CarBidUpdater
{
    public function __construct(
        private readonly SupplierAdapterInterface $supplierAdapter,
        private readonly DomainEventDispatcherInterface $eventDispatcher,
        private readonly DetailsRepositoryInterface $detailsRepository,
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly TransferCancelConditionsFactory $cancelConditionsFactory,
    ) {}

    public function add(BookingId $bookingId, CarBidDataDto $carData): void
    {
        $car = $this->supplierAdapter->findCar($carData->carId);
        if ($car === null) {
            throw new EntityNotFoundException('Car not found');
        }
        $booking = $this->bookingRepository->findOrFail($bookingId);
        $details = $this->detailsRepository->findOrFail($bookingId);

        $serviceDate = $details->serviceDate();
        if ($serviceDate === null) {
            throw new ServiceDateUndefined();
        }
        $carBidPrices = $this->buildCarBidPrices(
            $details->serviceInfo()->supplierId(),
            $details->serviceInfo()->id(),
            $carData->carId,
            $booking->prices()->clientPrice()->currency(),
            $serviceDate
        );

        $cancelConditions = $this->cancelConditionsFactory->build(
            $booking->cancelConditions(),
            $details->serviceInfo()->id(),
            $carData->carId,
            $carBidPrices->clientPrice()->valuePerCar(),
            $carData->carsCount,
            $serviceDate
        );
        $booking->setCancelConditions($cancelConditions);

        $carBid = CarBid::create(
            new CarId($carData->carId),
            $carData->carsCount,
            $carData->passengersCount,
            $carData->baggageCount,
            $carData->babyCount,
            $carBidPrices
        );
        $details->addCarBid($carBid);

        $this->bookingRepository->store($booking);
        $this->detailsRepository->store($details);
        $this->eventDispatcher->dispatch(new CarBidAdded($bookingId, $booking->orderId()));
    }

    public function update(BookingId $bookingId, string $carBidId, CarBidDataDto $carData): void
    {
        $car = $this->supplierAdapter->findCar($carData->carId);
        if ($car === null) {
            throw new EntityNotFoundException('Car not found');
        }
        $booking = $this->bookingRepository->findOrFail($bookingId);
        $details = $this->detailsRepository->findOrFail($bookingId);

        $serviceDate = $details->serviceDate();
        if ($serviceDate === null) {
            throw new ServiceDateUndefined();
        }
        $carBidPrices = $this->buildCarBidPrices(
            $details->serviceInfo()->supplierId(),
            $details->serviceInfo()->id(),
            $carData->carId,
            $booking->prices()->clientPrice()->currency(),
            $serviceDate,
        );

        $cancelConditions = $this->cancelConditionsFactory->build(
            $booking->cancelConditions(),
            $details->serviceInfo()->id(),
            $carData->carId,
            $carBidPrices->clientPrice()->valuePerCar(),
            $carData->carsCount,
            $serviceDate
        );
        $booking->setCancelConditions($cancelConditions);

        $carBid = new CarBid(
            $carBidId,
            new CarId($carData->carId),
            $carData->carsCount,
            $carData->passengersCount,
            $carData->baggageCount,
            $carData->babyCount,
            $carBidPrices
        );
        $details->replaceCarBid($carBidId, $carBid);

        $this->bookingRepository->store($booking);
        $this->detailsRepository->store($details);
        $this->eventDispatcher->dispatch(new CarBidUpdated($bookingId, $booking->orderId()));
    }

    public function remove(BookingId $bookingId, string $carBidId): void
    {
        $booking = $this->bookingRepository->findOrFail($bookingId);
        $details = $this->detailsRepository->findOrFail($bookingId);
        $details->removeCarBid($carBidId);
        if ($details->carBids()->count() === 0) {
            $booking->setCancelConditions(null);
        }

        $this->bookingRepository->store($booking);
        $this->detailsRepository->store($details);
        $this->eventDispatcher->dispatch(new CarBidRemoved($bookingId, $booking->orderId()));
    }

    private function buildCarBidPrices(
        int $supplierId,
        int $serviceId,
        int $carId,
        CurrencyEnum $clientCurrency,
        DateTimeInterface $date
    ): CarBidPrices {
        $price = $this->supplierAdapter->getTransferServicePrice(
            $supplierId,
            $serviceId,
            $carId,
            $clientCurrency,
            $date
        );
        if ($price === null) {
            throw new NotFoundTransferServicePrice();
        }

        $supplierPrice = $price->supplierPrice;
        $clientPrice = $price->clientPrice;

        return new CarBidPrices(
            supplierPrice: new CarBidPriceItem($supplierPrice->currency, $supplierPrice->amount),
            clientPrice: new CarBidPriceItem($clientPrice->currency, $clientPrice->amount)
        );
    }
}
