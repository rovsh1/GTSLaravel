<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Service\StatusRules;

use Module\Booking\Common\Domain\Service\RequestRules;
use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;

interface StatusRulesInterface
{
    public function canTransit(BookingStatusEnum $fromStatus, BookingStatusEnum $toStatus): bool;

    public function isEditableStatus(BookingStatusEnum $status): bool;

    public function isCancelledStatus(BookingStatusEnum $status): bool;

    public function isDeletedStatus(BookingStatusEnum $status): bool;

    public function canEditExternalNumber(BookingStatusEnum $status): bool;

    public function canChangeRoomPrice(BookingStatusEnum $status): bool;
}
