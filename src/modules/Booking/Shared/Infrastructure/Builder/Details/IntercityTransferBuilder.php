<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Builder\Details;

use Module\Booking\Shared\Infrastructure\Models\Details\Transfer;
use Sdk\Booking\Entity\BookingDetails\IntercityTransfer;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\CarBidCollection;
use Sdk\Booking\ValueObject\CityId;
use Sdk\Booking\ValueObject\DetailsId;

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
