<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Booking\Service\TransferBooking;

use Carbon\CarbonInterface;
use Module\Booking\Domain\Booking\Adapter\SupplierAdapterInterface;
use Module\Booking\Domain\Booking\Entity\CarRentWithDriver;
use Module\Booking\Domain\Booking\Entity\DayCarTrip;
use Module\Booking\Domain\Booking\Entity\IntercityTransfer;
use Module\Booking\Domain\Booking\Entity\ServiceDetailsInterface;
use Module\Booking\Domain\Booking\Entity\TransferFromAirport;
use Module\Booking\Domain\Booking\Entity\TransferFromRailway;
use Module\Booking\Domain\Booking\Entity\TransferToAirport;
use Module\Booking\Domain\Booking\Entity\TransferToRailway;
use Module\Booking\Domain\Booking\Event\CarBidAdded;
use Module\Booking\Domain\Booking\Event\CarBidRemoved;
use Module\Booking\Domain\Booking\Event\CarBidUpdated;
use Module\Booking\Domain\Booking\Exception\NotFoundTransferServicePrice;
use Module\Booking\Domain\Booking\Exception\ServiceDateUndefined;
use Module\Booking\Domain\Booking\Factory\DetailsRepositoryFactory;
use Module\Booking\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Booking\ValueObject\CarBid;
use Module\Booking\Domain\Booking\ValueObject\CarBid\CarBidPriceItem;
use Module\Booking\Domain\Booking\ValueObject\CarBid\CarBidPrices;
use Module\Booking\Domain\Booking\ValueObject\CarId;
use Module\Booking\Moderation\Application\Dto\CarBidDataDto;
use Module\Shared\Enum\CurrencyEnum;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class CarBidUpdater
{
    public function __construct(
        private readonly SupplierAdapterInterface $supplierAdapter,
        private readonly DomainEventDispatcherInterface $eventDispatcher,
        private readonly DetailsRepositoryFactory $detailsRepositoryFactory,
        private readonly BookingRepositoryInterface $bookingRepository,
    ) {}

    public function add(BookingId $bookingId, CarBidDataDto $carData): void
    {
        $car = $this->supplierAdapter->findCar($carData->carId);
        if ($car === null) {
            throw new EntityNotFoundException('Car not found');
        }
        $booking = $this->bookingRepository->findOrFail($bookingId);
        $repository = $this->detailsRepositoryFactory->buildByBookingId($bookingId);
        /** @var ServiceDetailsInterface $details */
        $details = $repository->find($bookingId);

        $serviceDate = $this->getServiceDate($details);
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
        $carBid = CarBid::create(
            new CarId($carData->carId),
            $carData->carsCount,
            $carData->passengersCount,
            $carData->baggageCount,
            $carData->babyCount,
            $carBidPrices
        );
        $details->addCarBid($carBid);
        $repository->store($details);
        $this->eventDispatcher->dispatch(new CarBidAdded($bookingId, $booking->orderId()));
    }

    public function update(BookingId $bookingId, string $carBidId, CarBidDataDto $carData): void
    {
        $car = $this->supplierAdapter->findCar($carData->carId);
        if ($car === null) {
            throw new EntityNotFoundException('Car not found');
        }
        $booking = $this->bookingRepository->findOrFail($bookingId);
        $repository = $this->detailsRepositoryFactory->buildByBookingId($bookingId);
        $details = $repository->find($bookingId);

        $serviceDate = $this->getServiceDate($details);
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
        $repository->store($details);
        $this->eventDispatcher->dispatch(new CarBidUpdated($bookingId, $booking->orderId()));
    }

    public function remove(BookingId $bookingId, string $carBidId): void
    {
        $booking = $this->bookingRepository->findOrFail($bookingId);
        $repository = $this->detailsRepositoryFactory->buildByBookingId($bookingId);
        $details = $repository->find($bookingId);
        $details->removeCarBid($carBidId);
        $repository->store($details);
        $this->eventDispatcher->dispatch(new CarBidRemoved($bookingId, $booking->orderId()));
    }

    private function buildCarBidPrices(
        int $supplierId,
        int $serviceId,
        int $carId,
        CurrencyEnum $clientCurrency,
        CarbonInterface $date
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

    private function getServiceDate(ServiceDetailsInterface $details): ?CarbonInterface
    {
        if ($details instanceof CarRentWithDriver) {
            return $details->bookingPeriod()?->dateFrom();
        } elseif ($details instanceof TransferToAirport) {
            return $details->departureDate();
        } elseif ($details instanceof TransferFromAirport) {
            return $details->arrivalDate();
        } elseif ($details instanceof TransferToRailway) {
            return $details->departureDate();
        } elseif ($details instanceof TransferFromRailway) {
            return $details->arrivalDate();
        } elseif ($details instanceof IntercityTransfer) {
            return $details->departureDate();
        } elseif ($details instanceof DayCarTrip) {
            return $details->departureDate();
        }
        throw new \RuntimeException('Unknown transfer type');
    }
}
