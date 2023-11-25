<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Dto;

final class UpdateStatusResponseDto
{
    public function __construct(
        public readonly bool $isNotConfirmedReasonRequired = false,
        public readonly bool $isCancelFeeAmountRequired = false,
    ) {}
}
