<?php

namespace Module\Hotel\Quotation\Infrastructure\Repository;

use Carbon\CarbonPeriod;
use Module\Hotel\Quotation\Domain\Repository\QuotaRepositoryInterface;
use Module\Hotel\Quotation\Domain\ValueObject\BookingId;
use Module\Hotel\Quotation\Domain\ValueObject\BookingPeriod;
use Module\Hotel\Quotation\Domain\ValueObject\RoomId;
use Module\Hotel\Quotation\Infrastructure\Model\Quota;
use Module\Hotel\Quotation\Infrastructure\Model\QuotaBooking;
use Sdk\Booking\Enum\QuotaChangeTypeEnum;
use Sdk\Shared\Enum\Hotel\QuotaStatusEnum;

class QuotaRepository implements QuotaRepositoryInterface
{
    public function update(RoomId $roomId, CarbonPeriod $period, ?int $quota, ?int $releaseDays = null): void
    {
        $roomId = $roomId->value();
        $updateData = ['date' => $period->getStartDate(), 'room_id' => $roomId];
        if ($quota !== null) {
            $updateData['count_total'] = $quota;
        }
        if ($releaseDays !== null) {
            $updateData['release_days'] = $releaseDays;
        }
        Quota::whereRoomId($roomId)
            ->updateOrCreate(['date' => $period->getStartDate(), 'room_id' => $roomId], $updateData);
    }

    public function close(RoomId $roomId, CarbonPeriod $period): void
    {
        Quota::batchUpdateStatus($roomId->value(), $period, QuotaStatusEnum::CLOSE);
    }

    public function open(RoomId $roomId, CarbonPeriod $period): void
    {
        Quota::batchUpdateStatus($roomId->value(), $period, QuotaStatusEnum::OPEN);
    }

    public function reset(RoomId $roomId, CarbonPeriod $period): void
    {
        Quota::whereRoomId($roomId->value())
            ->wherePeriod($period)
            ->delete();
    }

    public function hasAvailable(RoomId $roomId, BookingPeriod $period, int $count): bool
    {
        $releaseDays = now()->diffInDays($period->dateFrom());
        $isReleaseDaysAvailable = Quota::whereDate($period->dateFrom())
            ->whereRoomId($roomId->value())
            ->whereOpened()
            ->whereHasAvailable($count)
            ->whereReleaseDaysBelowOrEqual($releaseDays)
            ->exists();

        if (!$isReleaseDaysAvailable) {
            return false;
        }

        return Quota::wherePeriod($this->convertBookingPeriod($period))
            ->whereRoomId($roomId->value())
            ->whereOpened()
            ->whereHasAvailable($count)
            ->exists();
    }

    public function book(BookingId $bookingId, RoomId $roomId, BookingPeriod $period, int $count): void
    {
        QuotaBooking::batchInsert(
            type: QuotaChangeTypeEnum::BOOKED,
            bookingId: $bookingId->value(),
            roomId: $roomId->value(),
            period: $this->convertBookingPeriod($period),
            count: $count,
            context: []
        );
    }

    public function reserve(BookingId $bookingId, RoomId $roomId, BookingPeriod $period, int $count): void
    {
        QuotaBooking::batchInsert(
            type: QuotaChangeTypeEnum::RESERVED,
            bookingId: $bookingId->value(),
            roomId: $roomId->value(),
            period: $this->convertBookingPeriod($period),
            count: $count,
            context: []
        );
    }

    public function cancelBooking(BookingId $bookingId): void
    {
        QuotaBooking::whereBookingId($bookingId->value())->delete();
    }

    private function convertBookingPeriod(BookingPeriod $period): CarbonPeriod
    {
        return (new CarbonPeriod($period->dateFrom(), $period->dateTo(), 'P1D'))
            ->excludeEndDate();
    }
}
