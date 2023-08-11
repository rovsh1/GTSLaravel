<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Infrastructure\Repository;

use Carbon\CarbonImmutable;
use Carbon\CarbonPeriod;
use Carbon\CarbonPeriodImmutable;
use Module\Booking\Common\Domain\ValueObject\BookingId;
use Module\Booking\HotelBooking\Domain\Entity\Booking;
use Module\Booking\HotelBooking\Domain\Entity\RoomQuota as Entity;
use Module\Booking\HotelBooking\Domain\Repository\BookingQuotaRepositoryInterface;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\BookingPeriod;
use Module\Booking\HotelBooking\Domain\ValueObject\QuotaId;
use Module\Booking\HotelBooking\Infrastructure\Models\Hotel\RoomQuota as Model;
use Module\Hotel\Infrastructure\Models\Room\QuotaReservation;
use Module\Shared\Enum\Booking\QuotaChangeTypeEnum;
use Module\Shared\Support\Repository\CanTransactionTrait;

class BookingQuotaRepository implements BookingQuotaRepositoryInterface
{
    use CanTransactionTrait;

    /**
     * @param Booking $booking
     * @param CarbonPeriod $period
     * @return array<int, Entity>
     */
    public function getAvailableQuotas(Booking $booking): array
    {
        $preparedPeriod = $this->convertBookingPeriodToCarbonPeriod($booking->period());
        $roomQuotas = Model::wherePeriod($preparedPeriod)
            ->whereBookingId($booking->id()->value())
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
        );
    }

    public function book(BookingId $id, int $roomId, CarbonImmutable $date, int $count, array $context): void
    {
        $quotas = Model::whereRoomId($roomId)->wherePeriod($preparedPeriod)->withCountColumns();

        $values = [];
        foreach ($quotas->cursor() as $quota) {
            $values[] = new QuotaReservation([
                'quota_id' => $quota->id,
                'room_id' => $roomId,
                'booking_id' => $id->value(),
                'type' => QuotaChangeTypeEnum::BOOKED,
                'value' => $count,
                'context' => $context
            ]);
        }

        QuotaReservation::insert($values);
    }

    public function reserve(BookingId $id, int $roomId, CarbonImmutable $date, int $count, array $context): void
    {
        // TODO: Implement reserve() method.
    }

    public function resetByBookingId(BookingId $id): void
    {
        // TODO: Implement resetByBookingId() method.
    }

    public function resetRoomQuota(int $roomId, CarbonPeriod $period): void
    {
        // TODO: Implement resetRoomQuota() method.
    }

    public function convertBookingPeriodToCarbonPeriod(BookingPeriod $period): CarbonPeriodImmutable
    {
        return new CarbonPeriodImmutable($period->dateFrom(), $period->dateTo(), 'P1D');
    }
}
