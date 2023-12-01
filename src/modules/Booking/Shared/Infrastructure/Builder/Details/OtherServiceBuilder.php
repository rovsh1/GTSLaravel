<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Builder\Details;

use Module\Booking\Shared\Infrastructure\Models\Details\Other as Model;
use Sdk\Booking\Entity\Details\Other;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\DetailsId;
use Sdk\Module\Support\DateTimeImmutable;

class OtherServiceBuilder extends AbstractServiceDetailsBuilder
{
    public function build(Model $details): Other
    {
        $date = $details->data['date'] ?? null;

        return new Other(
            id: new DetailsId($details->id),
            bookingId: new BookingId($details->booking_id),
            serviceInfo: $this->buildServiceInfo($details->data['serviceInfo']),
            description: $details->data['description'],
            date: $date !== null ? DateTimeImmutable::createFromTimestamp($date) : null,
        );
    }
}
