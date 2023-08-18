<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Infrastructure\Repository;

use Carbon\CarbonPeriodImmutable;
use Module\Booking\Common\Domain\ValueObject\BookingId;
use Module\Booking\HotelBooking\Domain\Entity\RoomQuota as Entity;
use Module\Booking\HotelBooking\Domain\Repository\BookingQuotaRepositoryInterface;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\BookingPeriod;
use Module\Booking\HotelBooking\Domain\ValueObject\QuotaId;
use Module\Booking\HotelBooking\Infrastructure\Models\Hotel\RoomQuota as Model;
use Module\Hotel\Infrastructure\Models\Room\QuotaReservation;
use Module\Shared\Enum\Booking\QuotaChangeTypeEnum;

class BookingQuotaRepository implements BookingQuotaRepositoryInterface
{
    /**
     * @param BookingPeriod $period
     * @param int[] $roomIds
     * @return Entity[]
     */
    public function getAvailableQuotas(BookingPeriod $period, array $roomIds): array
    {
        $preparedPeriod = $this->convertBookingPeriodToCarbonPeriod($period);
        $roomQuotas = Model::wherePeriod($preparedPeriod)
            ->whereIn('room_id', $roomIds)
            ->get();

        return $roomQuotas->map(
            fn(Model $roomQuota) => new Entity(
                id: new QuotaId($roomQuota->id),
                roomId: $roomQuota->room_id,
                date: $roomQuota->date->toImmutable(),
                status: $roomQuota->status,
                countAvailable: $roomQuota->count_available,
                countTotal: $roomQuota->count_total
            )
        )->all();
    }

    public function book(BookingId $id, QuotaId $quotaId, int $count, array $context): void
    {
        QuotaReservation::create([
            'quota_id' => $quotaId->value(),
            'booking_id' => $id->value(),
            'type' => QuotaChangeTypeEnum::BOOKED,
            'value' => $count,
            'context' => $context
        ]);
    }

    public function reserve(BookingId $id, QuotaId $quotaId, int $count, array $context): void
    {
        QuotaReservation::create([
            'quota_id' => $quotaId->value(),
            'booking_id' => $id->value(),
            'type' => QuotaChangeTypeEnum::RESERVED,
            'value' => $count,
            'context' => $context
        ]);
    }

    public function resetByBookingId(BookingId $id): void
    {
        QuotaReservation::whereBookingId($id->value())->delete();
    }

    public function convertBookingPeriodToCarbonPeriod(BookingPeriod $period): CarbonPeriodImmutable
    {
        return new CarbonPeriodImmutable($period->dateFrom(), $period->dateTo(), 'P1D');
    }
}
