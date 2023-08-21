<?php

namespace Module\Booking\Common\Domain\Service\DocumentGenerator;

use Module\Booking\Common\Domain\Adapter\FileStorageAdapterInterface;
use Module\Booking\Common\Domain\Entity\BookingInterface;
use Module\Shared\Domain\Service\TemplateBuilder\ViewFactoryInterface;

abstract class AbstractDocumentGenerator
{
    public function __construct(
        protected readonly string $templatesPath,
        protected readonly FileStorageAdapterInterface $fileStorageAdapter,
        protected readonly ViewFactoryInterface $viewFactory,
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

    protected function getTemplateBuilder(): TemplateBuilder
    {
        return new TemplateBuilder($this->templatesPath, $this->getTemplateName(), $this->viewFactory);
    }

    abstract protected function getTemplateName(): string;

    abstract protected function getReservationAttributes(BookingInterface $booking): array;
}
