<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Factory\Details;

use DateTimeInterface;
use Sdk\Booking\Entity\BookingDetails\IntercityTransfer;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\CarBidCollection;
use Sdk\Booking\ValueObject\ServiceInfo;

interface IntercityTransferFactoryInterface
{
    public function create(
        BookingId $bookingId,
        ServiceInfo $serviceInfo,
        int $fromCityId,
        int $toCityId,
        CarBidCollection $carBids,
        bool $returnTripIncluded,
        ?DateTimeInterface $departureDate,
    ): IntercityTransfer;
}
