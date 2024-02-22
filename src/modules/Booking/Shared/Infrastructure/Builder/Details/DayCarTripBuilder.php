<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Builder\Details;

use Module\Booking\Shared\Infrastructure\Models\Details\Transfer;
use Sdk\Booking\Entity\Details\DayCarTrip;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\CityId;
use Sdk\Booking\ValueObject\DetailsId;
use Sdk\Module\Support\DateTimeImmutable;

class DayCarTripBuilder extends AbstractServiceDetailsBuilder
{
    public function build(Transfer $details): DayCarTrip
    {
        $detailsData = $details->data;

        return new DayCarTrip(
            id: new DetailsId($details->id),
            bookingId: new BookingId($details->booking_id),
            serviceInfo: $this->buildServiceInfo($detailsData['serviceInfo']),
            cityId: new CityId($detailsData['cityId']),
            destinationsDescription: $detailsData['destinationsDescription'],
            departureDate: $details->date_start !== null ? DateTimeImmutable::createFromInterface($details->date_start) : null,
        );
    }
}
