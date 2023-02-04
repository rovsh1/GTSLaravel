<?php

namespace GTS\Reservation\Domain\Service\DocumentGenerator;

use GTS\Reservation\Domain\Entity\Document\CancellationRequest;
use GTS\Reservation\Domain\Entity\ReservationRequestableInterface;

class CancellationRequestGenerator extends AbstractGenerator
{
    public function generate(ReservationRequestableInterface $reservation): CancellationRequest {}
}
