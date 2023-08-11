<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Domain\Entity;

use Carbon\CarbonImmutable;
use Module\Booking\HotelBooking\Domain\Service\QuotaManager\Model\QuotaInterface;
use Module\Booking\HotelBooking\Domain\ValueObject\QuotaId;
use Module\Hotel\Infrastructure\Models\Room\QuotaStatusEnum;
use Module\Shared\Domain\Entity\EntityInterface;

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
