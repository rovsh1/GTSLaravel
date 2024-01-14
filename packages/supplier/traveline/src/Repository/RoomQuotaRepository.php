<?php

declare(strict_types=1);

namespace Pkg\Supplier\Traveline\Repository;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Pkg\Supplier\Traveline\Dto\QuotaDto;
use Pkg\Supplier\Traveline\Models\HotelRoomQuota;
use Sdk\Shared\Enum\Hotel\QuotaStatusEnum;

class RoomQuotaRepository
{
    public function updateRoomQuota(int $roomId, CarbonPeriod $period, int $quota): void
    {
        $data = $this->prepareQuotaDataByPeriod($roomId, $period, quota: $quota);
        HotelRoomQuota::upsert(
            $data,
            ['room_id', 'date'],
            ['count_available', 'status']
        );
    }

    public function updateRoomReleaseDays(int $roomId, CarbonPeriod $period, int $releaseDays): void
    {
        $data = $this->prepareQuotaDataByPeriod($roomId, $period, releaseDays: $releaseDays);
        HotelRoomQuota::upsert(
            $data,
            ['room_id', 'date'],
            ['release_days']
        );
    }

    public function closeRoomQuota(int $roomId, CarbonPeriod $period): void
    {
        $data = $this->prepareQuotaDataByPeriod($roomId, $period, status: QuotaStatusEnum::CLOSE);
        HotelRoomQuota::upsert(
            $data,
            ['room_id', 'date'],
            ['status']
        );
    }

    public function openRoomQuota(int $roomId, CarbonPeriod $period): void
    {
        $data = $this->prepareQuotaDataByPeriod($roomId, $period, status: QuotaStatusEnum::OPEN);
        HotelRoomQuota::upsert(
            $data,
            ['room_id', 'date'],
            ['status']
        );
    }

    public function getAvailableCount(int $roomId, CarbonPeriod $period): int
    {
        $isQuotaAvailable = $this->hasAvailable($roomId, $period, 1);
        if (!$isQuotaAvailable) {
            return 0;
        }

        return (int)DB::table(
            HotelRoomQuota::query()->whereRoomId($roomId)->wherePeriod($period)->whereOpened(),
            't'
        )->min('t.count_available');
    }

    /**
     * @param int $hotelId
     * @param CarbonPeriod $period
     * @param int|null $roomId
     * @return QuotaDto[]
     */
    public function getAllQuotas(int $hotelId, CarbonPeriod $period, ?int $roomId): array
    {
        return $this->mapQuery(
            $this->bootQuery($hotelId, $period, $roomId)
        );
    }

    /**
     * @param int $hotelId
     * @param CarbonPeriod $period
     * @param int|null $roomId
     * @return QuotaDto[]
     */
    public function getAvailableQuotas(int $hotelId, CarbonPeriod $period, ?int $roomId): array
    {
        return $this->mapQuery(
            $this->bootQuery($hotelId, $period, $roomId)
                ->whereOpened()
                ->whereHasAvailable()
        );
    }

    /**
     * @param int $hotelId
     * @param CarbonPeriod $period
     * @param int|null $roomId
     * @return QuotaDto[]
     */
    public function getClosed(int $hotelId, CarbonPeriod $period, ?int $roomId): array
    {
        return $this->mapQuery(
            $this->bootQuery($hotelId, $period, $roomId)
                ->whereClosed()
                ->whereHasAvailable()
        );
    }

    /**
     * @param int $hotelId
     * @param CarbonPeriod $period
     * @param int|null $roomId
     * @return QuotaDto[]
     */
    public function getSold(int $hotelId, CarbonPeriod $period, ?int $roomId): array
    {
        return $this->mapQuery(
            $this->bootQuery($hotelId, $period, $roomId)
                ->whereSold()
                ->whereHasAvailable()
        );
    }

    public function hasAvailable(int $roomId, CarbonPeriod $period, int $count): bool
    {
        $releaseDays = now()->diffInDays($period->getStartDate());
        $isReleaseDaysAvailable = HotelRoomQuota::whereDate($period->getEndDate())
            ->whereRoomId($roomId)
            ->whereOpened()
            ->whereHasAvailable($count)
            ->whereReleaseDaysBelowOrEqual($releaseDays)
            ->exists();

        if (!$isReleaseDaysAvailable) {
            return false;
        }

        return HotelRoomQuota::wherePeriod($period)
            ->whereRoomId($roomId)
            ->whereOpened()
            ->whereHasAvailable($count)
            ->exists();
    }

    private function bootQuery(int $hotelId, CarbonPeriod $period, ?int $roomId): Builder|HotelRoomQuota
    {
        return HotelRoomQuota::query()
            ->whereHotelId($hotelId, $roomId)
            ->wherePeriod($period);
    }

    /**
     * @param Builder|HotelRoomQuota $query
     * @return QuotaDto[]
     */
    private function mapQuery(Builder|HotelRoomQuota $query): array
    {
        return $query
            ->get()
            ->map(fn(HotelRoomQuota $quota) => new QuotaDto(
                id: $quota->id,
                roomId: $quota->room_id,
                date: new Carbon($quota->date),
                status: $quota->status === QuotaStatusEnum::OPEN,
                releaseDays: $quota->release_days,
                countTotal: $quota->count_available,
                countAvailable: $quota->count_available,
                countBooked: 0,
                countReserved: 0,
            ))
            ->all();
    }

    private function prepareQuotaDataByPeriod(
        int $roomId,
        CarbonPeriod $period,
        ?int $quota = null,
        ?int $releaseDays = null,
        ?QuotaStatusEnum $status = null,
    ): array {
        $quotas = [];
        foreach ($period as $date) {
            $quotas[] = [
                'room_id' => $roomId,
                'date' => $date,
                'count_available' => $quota ?? 0,
                'release_days' => $releaseDays ?? 0,
                'status' => $status ?? ($quota > 0 ? QuotaStatusEnum::OPEN : QuotaStatusEnum::CLOSE),
            ];
        }

        return $quotas;
    }
}