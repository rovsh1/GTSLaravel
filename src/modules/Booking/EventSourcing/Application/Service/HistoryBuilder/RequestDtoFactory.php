<?php

namespace Module\Booking\EventSourcing\Application\Service\HistoryBuilder;

use Module\Booking\EventSourcing\Application\Dto\EventDto;
use Module\Booking\EventSourcing\Infrastructure\Model\BookingHistory;
use Sdk\Booking\Enum\RequestTypeEnum;
use Sdk\Shared\Contracts\Adapter\FileStorageAdapterInterface;
use Sdk\Shared\Contracts\Service\TranslatorInterface;

class RequestDtoFactory extends AbstractDtoFactory implements DtoFactoryInterface
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly FileStorageAdapterInterface $fileStorageAdapter,
    ) {}

    public function build(BookingHistory $history): EventDto
    {
        $request = $history->payload['request'];
        $fileDto = $this->fileStorageAdapter->find($request['file']);
        $type = RequestTypeEnum::from($request['type']);

        return $this->wrap(
            $history,
            'Отправлен '
            . $this->translator->translateEnum($type)
            . ' <a href="' . $fileDto->url . ' " class="ui-attachment-link" target="_blank">скачать</a>'
        );
    }
}