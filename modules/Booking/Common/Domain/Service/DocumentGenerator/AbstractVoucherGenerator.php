<?php

namespace Module\Booking\Common\Domain\Service\DocumentGenerator;

use Module\Booking\Common\Domain\Entity\BookingInterface;

abstract class AbstractVoucherGenerator
{
    final public function generate(BookingInterface $booking): string
    {
        return (new TemplateBuilder($this->getTemplateName()))
            ->attributes($this->getReservationAttributes($booking))
            ->generate();
    }

    abstract protected function getReservationAttributes(BookingInterface $booking): array;

    abstract protected function getTemplateName(): string;
}
