<?php

namespace Module\Booking\EventSourcing\Application\Service\HistoryBuilder;

use Module\Booking\EventSourcing\Infrastructure\Model\BookingHistory;
use Sdk\Booking\Enum\RequestTypeEnum;
use Sdk\Shared\Contracts\Adapter\FileStorageAdapterInterface;
use Sdk\Shared\Contracts\Service\TranslatorInterface;

class RequestDescriptor extends AbstractDescriptor implements DescriptorInterface
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly FileStorageAdapterInterface $fileStorageAdapter,
    ) {}

    public function build(BookingHistory $history): DescriptionDto
    {
        $type = RequestTypeEnum::from($history->payload['requestType']);
        $fileDto = $this->fileStorageAdapter->find($history->payload['fileGuid']);

        return new DescriptionDto(
            'Отправлен '
            . $this->translator->translateEnum($type)
            . ' ' . $this->fileLink($fileDto)
        );
    }
}