<?php

namespace Module\Booking\EventSourcing\Application\Service\HistoryBuilder;

use Module\Booking\EventSourcing\Application\Dto\EventDto;
use Module\Booking\EventSourcing\Infrastructure\Model\BookingHistory;

interface DtoFactoryInterface
{
    public function build(BookingHistory $history): EventDto;
}