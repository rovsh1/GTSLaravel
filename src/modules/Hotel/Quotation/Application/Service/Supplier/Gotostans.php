<?php

declare(strict_types=1);

namespace Module\Hotel\Quotation\Application\Service\Supplier;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Module\Hotel\Quotation\Application\Dto\QuotaDto;
use Module\Hotel\Quotation\Application\Service\SupplierQuotaBookerInterface;
use Module\Hotel\Quotation\Application\Service\SupplierQuotaFetcherInterface;
use Module\Hotel\Quotation\Application\Service\SupplierQuotaUpdaterInterface;
use Module\Hotel\Quotation\Domain\Repository\QuotaRepositoryInterface;
use Module\Hotel\Quotation\Domain\ValueObject\BookingId;
use Module\Hotel\Quotation\Domain\ValueObject\BookingPeriod;
use Module\Hotel\Quotation\Domain\ValueObject\RoomId;
use Module\Hotel\Quotation\Infrastructure\Model\Quota;
use Sdk\Shared\Enum\Hotel\QuotaStatusEnum;

class Gotostans implements SupplierQuotaFetcherInterface, SupplierQuotaUpdaterInterface, SupplierQuotaBookerInterface
{
    public function __construct(
        private readonly QuotaRepositoryInterface $quotaRepository,
    ) {}

    public function getAvailableCount(int $roomId, CarbonPeriod $period): int
    {
        $isQuotaAvailable = $this->quotaRepository->hasAvailable(
            new RoomId($roomId),
            new BookingPeriod(
                $period->getStartDate()->toDateTimeImmutable(),
                $period->getEndDate()->toDateTimeImmutable(),
            ),
            1
        );
        if (!$isQuotaAvailable) {
            return 0;
        }

        return (int)DB::table(
            Quota::whereRoomId($roomId)->wherePeriod($period)->whereOpened()->withCountColumns(),
            't'
        )->min('t.count_available');
    }

    public function getAll(int $hotelId, CarbonPeriod $period, ?int $roomId): array
    {
        return $this->mapQuery(
            $this->bootQuery($hotelId, $period, $roomId)
        );
    }

    public function getAvailable(int $hotelId, CarbonPeriod $period, ?int $roomId): array
    {
        return $this->mapQuery(
            $this->bootQuery($hotelId, $period, $roomId)
                ->whereOpened()
                ->whereHasAvailable()
        );
    }

    public function getClosed(int $hotelId, CarbonPeriod $period, ?int $roomId): array
    {
        return $this->mapQuery(
            $this->bootQuery($hotelId, $period, $roomId)
                ->whereClosed()
        );
    }

    public function getSold(int $hotelId, CarbonPeriod $period, ?int $roomId): array
    {
        return $this->mapQuery(
            $this->bootQuery($hotelId, $period, $roomId)
                ->whereSold()
        );
    }

    public function update(int $roomId, CarbonPeriod $period, ?int $quota, ?int $releaseDays = null): void
    {
        $this->quotaRepository->update(new RoomId($roomId), $period, $quota, $releaseDays);
    }

    public function open(int $roomId, CarbonPeriod $period): void
    {
        $this->quotaRepository->open(new RoomId($roomId), $period);
    }

    public function close(int $roomId, CarbonPeriod $period): void
    {
        $this->quotaRepository->close(new RoomId($roomId), $period);
    }

    public function resetRoomQuota(int $roomId, CarbonPeriod $period): void
    {
        $this->quotaRepository->reset(new RoomId($roomId), $period);
    }

    public function hasAvailable(int $roomId, CarbonPeriod $period, int $count): bool
    {
        $bookingPeriod = $this->convertCarbonToBookingPeriod($period);

        return $this->quotaRepository->hasAvailable(new RoomId($roomId), $bookingPeriod, $count);
    }

    public function book(int $bookingId, int $roomId, CarbonPeriod $period, int $count): void
    {
        $bookingPeriod = $this->convertCarbonToBookingPeriod($period);
        $this->quotaRepository->book(new BookingId($bookingId), new RoomId($roomId), $bookingPeriod, $count);
    }

    public function reserve(int $bookingId, int $roomId, CarbonPeriod $period, int $count): void
    {
        $bookingPeriod = $this->convertCarbonToBookingPeriod($period);
        $this->quotaRepository->reserve(new BookingId($bookingId), new RoomId($roomId), $bookingPeriod, $count);
    }

    public function cancelBooking(int $bookingId): void
    {
        $this->quotaRepository->cancelBooking(new BookingId($bookingId));
    }

    private function bootQuery(int $hotelId, CarbonPeriod $period, ?int $roomId): Builder|Quota
    {
        return Quota::query()
            ->whereHotelId($hotelId, $roomId)
            ->wherePeriod($period)
            ->withCountColumns();
    }

    /**
     * @param Builder|Quota $query
     * @return QuotaDto[]
     */
    private function mapQuery(Builder|Quota $query): array
    {
        return $query
            ->get()
            ->map(fn(Quota $quota) => new QuotaDto(
                id: $quota->id,
                roomId: $quota->room_id,
                date: new Carbon($quota->date),
                status: $quota->status === QuotaStatusEnum::OPEN,
                releaseDays: $quota->release_days,
                countTotal: $quota->count_total,
                countAvailable: $quota->count_available,
                countBooked: $quota->count_booked,
                countReserved: $quota->count_reserved
            ))
            ->all();
    }

    private function convertCarbonToBookingPeriod(CarbonPeriod $period): BookingPeriod
    {
        return new BookingPeriod(
            \DateTimeImmutable::createFromInterface($period->getStartDate()),
            \DateTimeImmutable::createFromInterface($period->getEndDate()),
        );
    }
}
