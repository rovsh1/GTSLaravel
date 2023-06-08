<?php

declare(strict_types=1);

namespace Module\Booking\Common\Application\Dto;

use Sdk\Module\Foundation\Support\Dto\Dto;

class UpdateStatusResponseDto extends Dto
{
    public function __construct(
        public readonly bool $isNotConfirmedReasonRequired = false,
        public readonly bool $isCancelFeeAmountRequired = false,
    ) {}
}
