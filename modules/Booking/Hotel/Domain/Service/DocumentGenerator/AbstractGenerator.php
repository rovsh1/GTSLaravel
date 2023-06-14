<?php

namespace Module\Booking\Hotel\Domain\Service\DocumentGenerator;

use Module\Booking\Common\Domain\Adapter\FileStorageAdapterInterface;
use Module\Booking\Common\Domain\Entity\AbstractBooking;
use Module\Booking\Common\Domain\Entity\Request;
use Module\Booking\Common\Domain\Entity\BookingInterface;
use Module\Booking\Common\Domain\Service\DocumentGenerator\DocumentGeneratorInterface;
use Module\Booking\Common\Domain\Service\DocumentGenerator\TemplateBuilder;

abstract class AbstractGenerator implements DocumentGeneratorInterface
{
    public function __construct(
        private readonly string $templatesPath,
        private readonly FileStorageAdapterInterface $fileStorageAdapter
    ) {}

    public function generate(Request $request, BookingInterface|AbstractBooking $booking): void
    {
        $documentContent = (new TemplateBuilder($this->templatesPath, $this->getTemplateName()))
            ->attributes($this->getReservationAttributes($booking))
            ->generate();

        $this->fileStorageAdapter->create(
            $request::class,
            $request->id()->value(),
            \Str::random(18) . '.pdf',
            $documentContent
        );
    }

    abstract protected function getTemplateName(): string;

    abstract protected function getReservationAttributes(AbstractBooking $booking): array;
}
