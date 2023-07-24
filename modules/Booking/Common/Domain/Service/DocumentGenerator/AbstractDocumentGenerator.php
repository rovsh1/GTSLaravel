<?php

namespace Module\Booking\Common\Domain\Service\DocumentGenerator;

use Module\Booking\Common\Domain\Adapter\FileStorageAdapterInterface;
use Module\Booking\Common\Domain\Entity\BookingInterface;

abstract class AbstractDocumentGenerator
{
    public function __construct(
        protected readonly string $templatesPath,
        protected readonly FileStorageAdapterInterface $fileStorageAdapter
    ) {}

    protected function getCompanyAttributes(): array
    {
        //@todo получить реквизиты компании
        return [
            'company' => 'ООО GotoStans',
            'phone' => '+99878 120-90-12',
            'email' => 'info@gotostans.com',
            'address' => 'Узбекистан, г.Ташкент, 100015, ул. Кичик Бешагач, д. 104А',
        ];
    }

    abstract protected function getTemplateName(): string;

    abstract protected function getReservationAttributes(BookingInterface $booking): array;
}
