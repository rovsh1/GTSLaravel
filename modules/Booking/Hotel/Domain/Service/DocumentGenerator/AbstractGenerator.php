<?php

namespace Module\Booking\Hotel\Domain\Service\DocumentGenerator;

use Module\Booking\Common\Domain\Entity\ReservationInterface;
use Module\Booking\Common\Domain\Service\DocumentGenerator\DocumentGeneratorInterface;
use Module\Booking\Common\Domain\Service\DocumentGenerator\TemplateBuilder;
use Module\Booking\Hotel\Domain\Entity\Booking;

abstract class AbstractGenerator implements DocumentGeneratorInterface
{
    public function generate(ReservationInterface|Booking $booking): string
    {
        return (new TemplateBuilder($this->getTemplateName()))
            ->attributes($this->getReservationAttributes($booking))
            ->generate();
    }

    abstract protected function getTemplateName(): string;

    abstract protected function getReservationAttributes(Booking $booking): array;

    private function getTemplateContents(): string
    {
        $filename = __DIR__ . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $this->getTemplateName();
        if (!file_exists($filename)) {
            throw new \LogicException('Request template [' . $this->getTemplateName() . '] undefined');
        }

        return file_get_contents($filename);
    }
}
