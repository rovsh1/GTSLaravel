<?php

namespace Module\Booking\Hotel\Domain\Service\DocumentGenerator;

use Module\Booking\Common\Domain\Entity\AbstractBooking;

class ChangeRequestGenerator extends AbstractGenerator
{
    protected function getTemplateName(): string
    {
        return 'hotel/change_request.html';
    }

    protected function getReservationAttributes(AbstractBooking $booking): array
    {
        return [
            'reservNumber' => $booking->id()->value(),
            'reservChangedAt' => now(),
            'reservCreatedAt' => $booking->createdAt()
        ];
    }
}
