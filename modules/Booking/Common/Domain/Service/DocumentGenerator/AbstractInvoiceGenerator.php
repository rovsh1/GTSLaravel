<?php

namespace Module\Booking\Common\Domain\Service\DocumentGenerator;

use Module\Booking\Common\Domain\Entity\ReservationInterface;

abstract class AbstractInvoiceGenerator
{
    final public function generate(ReservationInterface $booking): string
    {
        return (new TemplateBuilder($this->getTemplateName()))
            ->attributes($this->getReservationAttributes($booking))
            ->generate();
    }

    abstract protected function getReservationAttributes(ReservationInterface $reservation): array;

    abstract protected function getTemplateName(): string;
}
