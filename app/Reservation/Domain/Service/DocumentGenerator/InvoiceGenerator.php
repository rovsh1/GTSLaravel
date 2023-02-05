<?php

namespace GTS\Reservation\Domain\Service\DocumentGenerator;

use GTS\Reservation\Domain\Entity\Document\Invoice;
use GTS\Reservation\Domain\Entity\ReservationInterface;

class InvoiceGenerator extends AbstractGenerator
{
    public function generate(ReservationInterface $reservation): Invoice {}
}
