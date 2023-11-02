<?php

declare(strict_types=1);

namespace Module\Booking\Application\Dto;

use Sdk\Module\Foundation\Support\Dto\Dto;

class AvailableActionsDto extends Dto
{
    public function __construct(
        public readonly array $statuses,
        public readonly bool $isEditable,
        public readonly bool $isRequestable,
        public readonly bool $canSendBookingRequest,
        public readonly bool $canSendCancellationRequest,
        public readonly bool $canSendChangeRequest,
        public readonly bool $canEditExternalNumber,
        public readonly bool $canChangeRoomPrice,
        public readonly bool $canCopy,
    ) {}
}
