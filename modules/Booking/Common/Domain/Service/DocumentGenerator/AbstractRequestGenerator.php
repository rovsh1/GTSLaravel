<?php

namespace Module\Booking\Common\Domain\Service\DocumentGenerator;

use Module\Booking\Common\Domain\Event\Contracts\BookingRequestableInterface;

abstract class AbstractRequestGenerator
{
    final public function generate(BookingRequestableInterface $booking): string
    {
        return (new TemplateBuilder($this->getTemplateName()))
            ->attributes($this->getReservationAttributes($booking))
            ->generate();
    }

    abstract protected function getReservationAttributes(BookingRequestableInterface $reservation): array;

    abstract protected function getTemplateName(): string;
}
