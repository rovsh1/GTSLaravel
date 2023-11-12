<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Factory\Details;

use Module\Booking\Shared\Domain\Booking\Entity\DayCarTrip;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\CarBidCollection;
use Module\Booking\Shared\Domain\Booking\ValueObject\CityId;
use Module\Booking\Shared\Domain\Booking\ValueObject\DetailsId;
use Module\Booking\Shared\Infrastructure\Models\Details\Transfer;

class DayCarTripFactory extends AbstractServiceDetailsFactory
{
    public function build(Transfer $details): DayCarTrip
    {
        $detailsData = $details->data;

        return new DayCarTrip(
            id: new DetailsId($details->id),
            bookingId: new BookingId($details->bookingId()),
            serviceInfo: $this->buildServiceInfo($detailsData['serviceInfo']),
            cityId: new CityId($detailsData['cityId']),
            destinationsDescription: $detailsData['destinationsDescription'],
            departureDate: $details->date_start,
            carBids: CarBidCollection::fromData($detailsData['carBids'])
        );
    }
}
