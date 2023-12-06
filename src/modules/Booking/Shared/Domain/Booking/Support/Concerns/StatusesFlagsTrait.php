<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Support\Concerns;

use Sdk\Booking\Enum\StatusEnum;

trait StatusesFlagsTrait
{
    public function isConfirmed(): bool
    {
        return $this->status === StatusEnum::CONFIRMED;
    }

    public function isCancelled(): bool
    {
        return in_array($this->status, [
            StatusEnum::CANCELLED,
            StatusEnum::CANCELLED_FEE,
            StatusEnum::CANCELLED_NO_FEE,
//            BookingStatusEnum::DELETED,
//            BookingStatusEnum::WAITING_CANCELLATION,//@todo должно ли быть тут?
        ]);
    }

    public function inModeration(): bool
    {
        return !$this->isCancelled()
            && !$this->isConfirmed()
            && !$this->isDeleted();
    }

    public function isDeleted(): bool
    {
        return $this->status == StatusEnum::DELETED;
    }
}
