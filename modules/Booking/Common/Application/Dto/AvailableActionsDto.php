<?php

declare(strict_types=1);

namespace Module\Booking\Common\Application\Dto;

use Sdk\Module\Foundation\Support\Dto\Dto;

class AvailableActionsDto extends Dto
{
    public function __construct(
        public readonly array $statuses,
        public readonly bool $isRequestable,
        public readonly bool $canSendVoucher,
        public readonly bool $canCancel,
        public readonly bool $canEditExternalNumber,
    ) {}
}
