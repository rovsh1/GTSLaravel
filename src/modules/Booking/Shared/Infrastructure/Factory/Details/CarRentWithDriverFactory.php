<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Factory\Details;

use Module\Booking\Shared\Domain\Booking\Entity\CarRentWithDriver;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingPeriod;
use Module\Booking\Shared\Domain\Booking\ValueObject\CarBidCollection;
use Module\Booking\Shared\Domain\Booking\ValueObject\CityId;
use Module\Booking\Shared\Domain\Booking\ValueObject\DetailsId;
use Module\Booking\Shared\Infrastructure\Models\Details\Transfer;

class CarRentWithDriverFactory extends AbstractServiceDetailsFactory
{
    public function build(Transfer $details): CarRentWithDriver
    {
        $detailsData = $details->data;

        return new CarRentWithDriver(
            id: new DetailsId($details->id),
            bookingId: new BookingId($details->booking_id),
            serviceInfo: $this->buildServiceInfo($detailsData['serviceInfo']),
            cityId: new CityId($detailsData['cityId']),
            meetingAddress: $detailsData['meetingAddress'] ?? null,
            meetingTablet: $detailsData['meetingTablet'] ?? null,
            bookingPeriod: $details->date_start !== null && $details->date_end !== null
                ? new BookingPeriod($details->date_start, $details->date_end)
                : null,
            carBids: CarBidCollection::fromData($detailsData['carBids'])
        );
    }
}
