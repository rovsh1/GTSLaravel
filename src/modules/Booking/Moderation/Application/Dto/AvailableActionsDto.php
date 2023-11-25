<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Dto;

final class AvailableActionsDto
{
    public function __construct(
        public readonly array $statuses,
        public readonly bool $isEditable,
        public readonly bool $canEditExternalNumber,
        public readonly bool $canChangeRoomPrice,
        public readonly bool $canCopy,
    ) {
    }
}
