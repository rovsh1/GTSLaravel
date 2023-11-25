<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Domain\Booking\Service\StatusRules;

use Sdk\Shared\Enum\Booking\BookingStatusEnum;

interface StatusTransitionsInterface
{
    public function canTransit(BookingStatusEnum $fromStatus, BookingStatusEnum $toStatus): bool;

    /**
     * @param BookingStatusEnum $statusEnum
     * @return BookingStatusEnum[]
     */
    public function getAvailableTransitions(BookingStatusEnum $statusEnum): array;
}
