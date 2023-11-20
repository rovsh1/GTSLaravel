<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Builder\Details;

use Module\Booking\Shared\Domain\Booking\Entity\IntercityTransfer;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\CarBidCollection;
use Module\Booking\Shared\Domain\Booking\ValueObject\CityId;
use Module\Booking\Shared\Domain\Booking\ValueObject\DetailsId;
use Module\Booking\Shared\Infrastructure\Models\Details\Transfer;

class IntercityTransferBuilder extends AbstractServiceDetailsBuilder
{
    public function build(Transfer $details): IntercityTransfer
    {
        $detailsData = $details->data;

        return new IntercityTransfer(
            id: new DetailsId($details->id),
            bookingId: new BookingId($details->booking_id),
            serviceInfo: $this->buildServiceInfo($detailsData['serviceInfo']),
            fromCityId: new CityId($detailsData['fromCityId']),
            toCityId: new CityId($detailsData['toCityId']),
            returnTripIncluded: $detailsData['returnTripIncluded'] ?? false,
            departureDate: $details->date_start,
            carBids: CarBidCollection::fromData($detailsData['carBids'])
        );
    }
}
