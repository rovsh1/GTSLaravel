<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\HotelBooking\Repository;

use Carbon\CarbonPeriodImmutable;
use Module\Booking\Shared\Domain\Booking\Entity\HotelBooking\RoomQuota as Entity;
use Module\Booking\Shared\Domain\Booking\Repository\HotelBooking\BookingQuotaRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\BookingPeriod;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\QuotaId;
use Module\Booking\Shared\Infrastructure\HotelBooking\Models\Hotel\RoomQuota as Model;
use Module\Booking\Shared\Infrastructure\HotelBooking\Models\QuotaReservation;
use Module\Shared\Contracts\Service\ApplicationContextInterface;
use Module\Shared\Enum\Booking\QuotaChangeTypeEnum;

class BookingQuotaRepository implements BookingQuotaRepositoryInterface
{
    public function __construct(
        private readonly ApplicationContextInterface $context,
    ) {}

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
                countTotal: $roomQuota->count_total,
                countAvailable: $roomQuota->count_available
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
            'context' => $this->context->toArray($context)
        ]);
    }

    public function reserve(BookingId $id, QuotaId $quotaId, int $count, array $context): void
    {
        QuotaReservation::create([
            'quota_id' => $quotaId->value(),
            'booking_id' => $id->value(),
            'type' => QuotaChangeTypeEnum::RESERVED,
            'value' => $count,
            'context' => $this->context->toArray($context)
        ]);
    }

    public function cancel(BookingId $id): void
    {
        QuotaReservation::whereBookingId($id->value())->delete();
    }

    public function convertBookingPeriodToCarbonPeriod(BookingPeriod $period): CarbonPeriodImmutable
    {
        return new CarbonPeriodImmutable($period->dateFrom(), $period->dateTo(), 'P1D');
    }
}
