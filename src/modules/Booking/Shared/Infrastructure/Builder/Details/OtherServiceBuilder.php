<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Builder\Details;

use Module\Booking\Shared\Domain\Booking\Entity\Other;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\DetailsId;
use Module\Booking\Shared\Infrastructure\Models\Details\Other as Model;

class OtherServiceBuilder extends AbstractServiceDetailsBuilder
{
    public function build(Model $details): Other
    {
        return new Other(
            id: new DetailsId($details->id),
            bookingId: new BookingId($details->booking_id),
            serviceInfo: $this->buildServiceInfo($details->data['serviceInfo']),
            description: $details->data['description']
        );
    }
}
