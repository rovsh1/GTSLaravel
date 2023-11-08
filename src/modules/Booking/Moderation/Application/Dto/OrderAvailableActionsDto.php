<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Dto;

final class OrderAvailableActionsDto
{
    public function __construct(
        public readonly array $statuses,
        public readonly bool $isEditable,
        public readonly bool $canSendVoucher,
        public readonly bool $canSendInvoice,
    ) {}
}
