<?php

namespace GTS\Reservation\Domain\Service\DocumentGenerator;

use GTS\Reservation\Domain\Entity\Document\ChangeRequest;
use GTS\Reservation\Domain\Entity\ReservationRequestableInterface;

class ChangeRequestGenerator
{
    public function generate(ReservationRequestableInterface $document): ChangeRequest {}
}
