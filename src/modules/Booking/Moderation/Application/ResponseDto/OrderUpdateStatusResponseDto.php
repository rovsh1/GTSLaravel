<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\ResponseDto;

final class OrderUpdateStatusResponseDto
{
    public function __construct(
        public readonly bool $isRefundFeeAmountRequired = false,
    ) {}
}
