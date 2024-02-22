<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Domain\Booking\Service\StatusRules;

use Sdk\Booking\Enum\StatusEnum;

interface StatusTransitionsInterface
{
    public function canTransit(StatusEnum $fromStatus, StatusEnum $toStatus): bool;

    /**
     * @param StatusEnum $statusEnum
     * @return StatusEnum[]
     */
    public function getAvailableTransitions(StatusEnum $statusEnum): array;
}
