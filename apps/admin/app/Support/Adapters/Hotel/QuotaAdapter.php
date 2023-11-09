<?php

namespace App\Admin\Support\Adapters\Hotel;

use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use Module\Hotel\Quotation\Application\UseCase\CloseRoomQuota;
use Module\Hotel\Quotation\Application\UseCase\GetAvailableQuotas;
use Module\Hotel\Quotation\Application\UseCase\GetQuotas;
use Module\Hotel\Quotation\Application\UseCase\GetSoldQuotas;
use Module\Hotel\Quotation\Application\UseCase\GetStoppedQuotas;
use Module\Hotel\Quotation\Application\UseCase\OpenRoomQuota;
use Module\Hotel\Quotation\Application\UseCase\ResetRoomQuota;
use Module\Hotel\Quotation\Application\UseCase\UpdateRoomQuota;

class QuotaAdapter
{
    public function getQuotas(int $hotelId, CarbonPeriod $period, ?int $roomId = null): array
    {
        return app(GetQuotas::class)->execute($hotelId, $period, $roomId);
    }

    public function getAvailableQuotas(int $hotelId, CarbonPeriod $period, ?int $roomId = null): array
    {
        return app(GetAvailableQuotas::class)->execute($hotelId, $period, $roomId);
    }

    public function getSoldQuotas(int $hotelId, CarbonPeriod $period, ?int $roomId = null): array
    {
        return app(GetSoldQuotas::class)->execute($hotelId, $period, $roomId);
    }

    public function getStoppedQuotas(int $hotelId, CarbonPeriod $period, ?int $roomId = null): array
    {
        return app(GetStoppedQuotas::class)->execute($hotelId, $period, $roomId);
    }

    public function updateRoomQuota(int $roomId, CarbonInterface $date, ?int $count, ?int $releaseDays = null): void
    {
        app(UpdateRoomQuota::class)->execute($roomId, $this->getPeriodByDate($date), $count, $releaseDays);
    }

    public function openRoomQuota(int $roomId, CarbonInterface $date): void
    {
        app(OpenRoomQuota::class)->execute($roomId, $this->getPeriodByDate($date));
    }

    public function closeRoomQuota(int $roomId, CarbonInterface $date): void
    {
        app(CloseRoomQuota::class)->execute($roomId, $this->getPeriodByDate($date));
    }

    public function resetRoomQuota(int $roomId, CarbonInterface $date): void
    {
        app(ResetRoomQuota::class)->execute($roomId, $this->getPeriodByDate($date));
    }

    private function getPeriodByDate(CarbonInterface $date): CarbonPeriod
    {
        return new CarbonPeriod($date->clone()->startOfDay(), $date->clone()->endOfDay());
    }
}
