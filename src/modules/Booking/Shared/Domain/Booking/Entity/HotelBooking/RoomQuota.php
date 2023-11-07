<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Entity\HotelBooking;

use Carbon\CarbonImmutable;
use Module\Booking\Shared\Domain\Booking\Service\HotelBooking\QuotaManager\Model\QuotaInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\QuotaId;
use Module\Catalog\Infrastructure\Models\Room\QuotaStatusEnum;
use Module\Shared\Contracts\Domain\EntityInterface;

class RoomQuota implements EntityInterface, QuotaInterface
{
    public function __construct(
        private readonly QuotaId $id,
        private readonly int $roomId,
        private readonly CarbonImmutable $date,
        private readonly QuotaStatusEnum $status,
        private readonly int $countTotal,
        private readonly int $countAvailable,
    ) {
    }

    public function id(): QuotaId
    {
        return $this->id;
    }

    public function roomId(): int
    {
        return $this->roomId;
    }

    public function date(): CarbonImmutable
    {
        return $this->date;
    }

    public function status(): QuotaStatusEnum
    {
        return $this->status;
    }

    public function countTotal(): int
    {
        return $this->countTotal;
    }

    public function countAvailable(): int
    {
        return $this->countAvailable;
    }
}
