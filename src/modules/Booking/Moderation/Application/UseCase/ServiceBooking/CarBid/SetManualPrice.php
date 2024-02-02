<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\ServiceBooking\CarBid;

use Module\Booking\Shared\Domain\Booking\DbContext\CarBidDbContextInterface;
use Module\Booking\Shared\Domain\Booking\Service\BookingUnitOfWorkInterface;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\CarBid\CarBidPriceItem;
use Sdk\Booking\ValueObject\CarBid\CarBidPrices;
use Sdk\Booking\ValueObject\CarBidId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class SetManualPrice implements UseCaseInterface
{
    public function __construct(
        private readonly CarBidDbContextInterface $carBidDbContext,
        private readonly BookingUnitOfWorkInterface $bookingUnitOfWork,
    ) {}

    public function execute(
        int $bookingId,
        int $carBidId,
        ?float $clientPerCarPrice,
        ?float $supplierPerCarPrice = null
    ): void {
        $booking = $this->bookingUnitOfWork->findOrFail(new BookingId($bookingId));
        $currentCarBid = $this->carBidDbContext->findOrFail(new CarBidId($carBidId));
        $this->bookingUnitOfWork->persist($currentCarBid);

        $supplierPrice = $currentCarBid->prices()->supplierPrice();
        $clientPrice = $currentCarBid->prices()->clientPrice();

        $newPrice = new CarBidPrices(
            supplierPrice: new CarBidPriceItem(
                $supplierPrice->currency(),
                $supplierPrice->valuePerCar(),
                $supplierPerCarPrice
            ),
            clientPrice: new CarBidPriceItem(
                $clientPrice->currency(),
                $clientPrice->valuePerCar(),
                $clientPerCarPrice
            ),
        );
        $currentCarBid->setPrices($newPrice);

        $this->bookingUnitOfWork->touch($booking->id());
        $this->bookingUnitOfWork->commit();
    }
}
