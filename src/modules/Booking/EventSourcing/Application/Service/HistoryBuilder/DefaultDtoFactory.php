<?php

namespace Module\Booking\EventSourcing\Application\Service\HistoryBuilder;

use Module\Booking\EventSourcing\Application\Dto\EventDto;
use Module\Booking\EventSourcing\Infrastructure\Model\BookingHistory;

class DefaultDtoFactory extends AbstractDtoFactory implements DtoFactoryInterface
{
    public function build(BookingHistory $history): EventDto
    {
        return $this->wrap(
            $history,
            $history->payload['@event']
        );
    }
}