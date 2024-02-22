<?php

declare(strict_types=1);

namespace Pkg\Booking\Requesting\Application\Dto;

final class AvailableActionsDto
{
    public function __construct(
        public readonly bool $isRequestable,
        public readonly bool $canSendBookingRequest,
        public readonly bool $canSendCancellationRequest,
        public readonly bool $canSendChangeRequest,
    ) {}
}
