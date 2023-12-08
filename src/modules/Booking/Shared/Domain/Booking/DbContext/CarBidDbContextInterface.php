<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\DbContext;

use Module\Supplier\Moderation\Domain\Supplier\ValueObject\CarId;
use Sdk\Booking\Entity\CarBid;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\CarBid\CarBidDetails;
use Sdk\Booking\ValueObject\CarBid\CarBidPrices;
use Sdk\Booking\ValueObject\CarBidCollection;
use Sdk\Booking\ValueObject\CarBidId;

interface CarBidDbContextInterface
{
    public function find(CarBidId $id): ?CarBid;

    public function findOrFail(CarBidId $id): CarBid;

    public function getByBookingId(BookingId $bookingId): CarBidCollection;

    public function store(CarBid $carBid): void;

    public function delete(CarBidId $id): void;

    public function create(
        BookingId $bookingId,
        CarId $carId,
        CarBidDetails $details,
        CarBidPrices $prices
    ): CarBid;
}
