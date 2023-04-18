<?php

namespace Module\Booking\Hotel\Domain\Service\DocumentGenerator;

use Module\Booking\Hotel\Domain\Entity\Reservation;

class ChangeRequestGenerator extends AbstractGenerator
{
    protected function getTemplateName(): string
    {
        return 'change_request.html';
    }

    protected function getReservationAttributes(Reservation $reservation): array
    {
        return [];
    }
}
