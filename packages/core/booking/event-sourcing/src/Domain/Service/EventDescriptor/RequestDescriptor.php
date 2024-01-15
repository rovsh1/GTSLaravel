<?php

namespace Pkg\Booking\EventSourcing\Domain\Service\EventDescriptor;

use Pkg\Booking\EventSourcing\Domain\ValueObject\EventGroupEnum;
use Sdk\Booking\IntegrationEvent\RequestSent;
use Sdk\Shared\Contracts\Adapter\FileStorageAdapterInterface;
use Sdk\Shared\Contracts\Event\IntegrationEventInterface;
use Sdk\Shared\Contracts\Service\TranslatorInterface;

class RequestDescriptor extends AbstractDescriptor implements DescriptorInterface
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly FileStorageAdapterInterface $fileStorageAdapter,
    ) {}

    public function build(IntegrationEventInterface $event): DescriptionDto
    {
        assert($event instanceof RequestSent);

        return new DescriptionDto(
            group: EventGroupEnum::REQUEST_SENT,
            field: 'requests',
            description: $this->buildDescription($event),
            before: null,
            after: null
        );
    }

    private function buildDescription(RequestSent $event): string
    {
        $fileDto = $this->fileStorageAdapter->find($event->fileGuid);

        return 'Отправлен '
            . $this->translator->translateEnum($event->requestType)
            . ' ' . $this->fileLink($fileDto);
    }
}