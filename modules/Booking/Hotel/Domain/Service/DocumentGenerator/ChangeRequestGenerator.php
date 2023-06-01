<?php

namespace Module\Booking\Hotel\Domain\Service\DocumentGenerator;

use Module\Booking\Common\Domain\Entity\Booking;

class ChangeRequestGenerator extends AbstractGenerator
{
    protected function getTemplateName(): string
    {
        return 'change_request.html';
    }

    protected function getReservationAttributes(Booking $booking): array
    {
        return [];
    }
}
