<?php

namespace GTS\Reservation\Common\Domain\Service\DocumentGenerator;

use GTS\Reservation\Common\Domain\Entity\ReservationRequestableInterface;

abstract class AbstractRequestGenerator
{
    final public function generate(ReservationRequestableInterface $reservation): string
    {
        return (new TemplateBuilder($this->getTemplateName()))
            ->attributes($this->getReservationAttributes($reservation))
            ->generate();
    }

    abstract protected function getReservationAttributes(ReservationRequestableInterface $reservation): array;

    abstract protected function getTemplateName(): string;
}
