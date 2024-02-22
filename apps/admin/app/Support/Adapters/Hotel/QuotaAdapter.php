<?php

namespace App\Admin\Support\Adapters\Hotel;

use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use Module\Hotel\Quotation\Application\UseCase\CloseQuota;
use Module\Hotel\Quotation\Application\UseCase\GetAvailableQuotas;
use Module\Hotel\Quotation\Application\UseCase\GetClosedQuotas;
use Module\Hotel\Quotation\Application\UseCase\GetQuotas;
use Module\Hotel\Quotation\Application\UseCase\GetSoldQuotas;
use Module\Hotel\Quotation\Application\UseCase\OpenQuota;
use Module\Hotel\Quotation\Application\UseCase\ResetQuota;
use Module\Hotel\Quotation\Application\UseCase\UpdateQuota;

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
        return app(GetClosedQuotas::class)->execute($hotelId, $period, $roomId);
    }

    public function updateRoomQuota(int $roomId, CarbonInterface $date, ?int $count, ?int $releaseDays = null): void
    {
        app(UpdateQuota::class)->execute($roomId, $this->getPeriodByDate($date), $count, $releaseDays);
    }

    public function openRoomQuota(int $roomId, CarbonInterface $date): void
    {
        app(OpenQuota::class)->execute($roomId, $this->getPeriodByDate($date));
    }

    public function closeRoomQuota(int $roomId, CarbonInterface $date): void
    {
        app(CloseQuota::class)->execute($roomId, $this->getPeriodByDate($date));
    }

    public function resetRoomQuota(int $roomId, CarbonInterface $date): void
    {
        app(ResetQuota::class)->execute($roomId, $this->getPeriodByDate($date));
    }

    private function getPeriodByDate(CarbonInterface $date): CarbonPeriod
    {
        return new CarbonPeriod($date->clone()->startOfDay(), $date->clone()->endOfDay());
    }
}
