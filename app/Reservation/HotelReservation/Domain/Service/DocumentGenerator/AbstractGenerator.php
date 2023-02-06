<?php

namespace GTS\Reservation\HotelReservation\Domain\Service\DocumentGenerator;

use GTS\Reservation\Common\Domain\Service\DocumentGenerator\TemplateBuilder;
use GTS\Reservation\HotelReservation\Domain\Entity\Reservation;

abstract class AbstractGenerator
{
    public function generate(Reservation $reservation): string
    {
        return (new TemplateBuilder($this->getTemplateContents()))
            ->attributes($this->getReservationAttributes($reservation))
            ->generate();
    }

    abstract protected function getTemplateName(): string;

    abstract protected function getReservationAttributes(Reservation $reservation): array;

    private function getTemplateContents(): string
    {
        $filename = __DIR__ . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $this->getTemplateName();
        if (!file_exists($filename))
            throw new \LogicException('Request template [' . $this->getTemplateName() . '] undefined');

        return file_get_contents($filename);
    }
}
