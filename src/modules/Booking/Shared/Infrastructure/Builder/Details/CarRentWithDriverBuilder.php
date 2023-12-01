<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Builder\Details;

use Module\Booking\Shared\Infrastructure\Models\Details\Transfer;
use Sdk\Booking\Entity\Details\CarRentWithDriver;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\BookingPeriod;
use Sdk\Booking\ValueObject\CarBidCollection;
use Sdk\Booking\ValueObject\CityId;
use Sdk\Booking\ValueObject\DetailsId;

class CarRentWithDriverBuilder extends AbstractServiceDetailsBuilder
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
