<?php

declare(strict_types=1);

namespace Module\Booking\Application\Dto;

use Sdk\Module\Foundation\Support\Dto\Dto;

class OrderAvailableActionsDto extends Dto
{
    public function __construct(
        public readonly array $statuses,
        public readonly bool $isEditable,
        public readonly bool $canSendVoucher,
        public readonly bool $canSendInvoice,
    ) {}
}
