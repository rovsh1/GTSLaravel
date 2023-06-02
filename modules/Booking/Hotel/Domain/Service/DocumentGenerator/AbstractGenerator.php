<?php

namespace Module\Booking\Hotel\Domain\Service\DocumentGenerator;

use Module\Booking\Common\Domain\Entity\Booking;
use Module\Booking\Common\Domain\Entity\ReservationInterface;
use Module\Booking\Common\Domain\Service\DocumentGenerator\DocumentGeneratorInterface;
use Module\Booking\Common\Domain\Service\DocumentGenerator\TemplateBuilder;

abstract class AbstractGenerator implements DocumentGeneratorInterface
{
    public function generate(ReservationInterface|Booking $booking): string
    {
        dd($this->module->config('templates_path'));
        return (new TemplateBuilder($this->module->config('templates_path'), $this->getTemplateName()))
            ->attributes($this->getReservationAttributes($booking))
            ->generate();
    }

    abstract protected function getTemplateName(): string;

    abstract protected function getReservationAttributes(Booking $booking): array;
}
