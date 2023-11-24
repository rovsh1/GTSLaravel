<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Support\Concerns;

use Module\Shared\Enum\Booking\BookingStatusEnum;

trait StatusesFlagsTrait
{
    public function isConfirmed(): bool
    {
        return $this->status === BookingStatusEnum::CONFIRMED;
    }

    public function isCancelled(): bool
    {
        return in_array($this->status, [
            BookingStatusEnum::CANCELLED,
            BookingStatusEnum::CANCELLED_FEE,
            BookingStatusEnum::CANCELLED_NO_FEE,
//            BookingStatusEnum::DELETED,
//            BookingStatusEnum::WAITING_CANCELLATION,//@todo должно ли быть тут?
        ]);
    }

    public function inModeration(): bool
    {
        return $this->isCancelled()
            && !$this->isConfirmed()
            && !$this->isDeleted();
    }

    public function isDeleted(): bool
    {
        return $this->status == BookingStatusEnum::DELETED;
    }
}
