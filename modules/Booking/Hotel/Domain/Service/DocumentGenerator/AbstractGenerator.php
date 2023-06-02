<?php

namespace Module\Booking\Hotel\Domain\Service\DocumentGenerator;

use Module\Booking\Common\Domain\Entity\Booking;
use Module\Booking\Common\Domain\Entity\ReservationInterface;
use Module\Booking\Common\Domain\Service\DocumentGenerator\DocumentGeneratorInterface;
use Module\Booking\Common\Domain\Service\DocumentGenerator\TemplateBuilder;

abstract class AbstractGenerator implements DocumentGeneratorInterface
{
    public function __construct(private readonly string $templatesPath) {}

    public function generate(ReservationInterface|Booking $booking): string
    {
        return (new TemplateBuilder($this->templatesPath, $this->getTemplateName()))
            ->attributes($this->getReservationAttributes($booking))
            ->generate();
    }

    abstract protected function getTemplateName(): string;

    abstract protected function getReservationAttributes(Booking $booking): array;
}
