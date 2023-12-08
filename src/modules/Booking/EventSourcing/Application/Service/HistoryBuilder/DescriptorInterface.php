<?php

namespace Module\Booking\EventSourcing\Application\Service\HistoryBuilder;

use Module\Booking\EventSourcing\Infrastructure\Model\BookingHistory;

interface DescriptorInterface
{
    public function build(BookingHistory $history): DescriptionDto;
}