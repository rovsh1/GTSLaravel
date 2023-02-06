<?php

namespace GTS\Reservation\Common\Domain\Service\DocumentGenerator;

use GTS\Reservation\Common\Domain\Entity\ReservationInterface;

abstract class AbstractVoucherGenerator
{
    final public function generate(ReservationInterface $reservation): string
    {
        return (new TemplateBuilder($this->getTemplateName()))
            ->attributes($this->getReservationAttributes($reservation))
            ->generate();
    }

    abstract protected function getReservationAttributes(ReservationInterface $reservation): array;

    abstract protected function getTemplateName(): string;
}
