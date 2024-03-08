<?php

declare(strict_types=1);

namespace Shared\Support\Adapter;

use Carbon\CarbonPeriod;
use Pkg\Supplier\Traveline\UseCase\CancelBookingQuotas;
use Pkg\Supplier\Traveline\UseCase\CheckHasAvailable;
use Pkg\Supplier\Traveline\UseCase\CheckHotelIntegrationEnabled;
use Pkg\Supplier\Traveline\UseCase\GetAvailableCount;
use Pkg\Supplier\Traveline\UseCase\GetAvailableQuotas;
use Pkg\Supplier\Traveline\UseCase\GetClosedQuotas;
use Pkg\Supplier\Traveline\UseCase\GetQuotas;
use Pkg\Supplier\Traveline\UseCase\GetSoldQuotas;
use Pkg\Supplier\Traveline\UseCase\QuotaAvailability;
use Pkg\Supplier\Traveline\UseCase\ReserveQuotas;
use Shared\Contracts\Adapter\TravelineAdapterInterface;

class TravelineAdapter implements TravelineAdapterInterface
{
    public function isHotelIntegrationEnabled(int $hotelId): bool
    {
        return app(CheckHotelIntegrationEnabled::class)->execute($hotelId);
    }

    public function getAvailableCount(int $roomId, CarbonPeriod $period): int
    {
        return app(GetAvailableCount::class)->execute($roomId, $period);
    }

    public function getAllQuotas(int $hotelId, CarbonPeriod $period, ?int $roomId): array
    {
        return app(GetQuotas::class)->execute($hotelId, $period, $roomId);
    }

    public function getAvailableQuotas(int $hotelId, CarbonPeriod $period, ?int $roomId): array
    {
        return app(GetAvailableQuotas::class)->execute($hotelId, $period, $roomId);
    }

    public function getClosedQuotas(int $hotelId, CarbonPeriod $period, ?int $roomId): array
    {
        return app(GetClosedQuotas::class)->execute($hotelId, $period, $roomId);
    }

    public function getSoldQuotas(int $hotelId, CarbonPeriod $period, ?int $roomId): array
    {
        return app(GetSoldQuotas::class)->execute($hotelId, $period, $roomId);
    }

    public function getQuotasAvailability(
        CarbonPeriod $period,
        array $cityIds = [],
        array $hotelIds = [],
        array $roomIds = []
    ): array {
        return app(QuotaAvailability\Get::class)->execute($period, $cityIds, $hotelIds, $roomIds);
    }

    public function update(int $roomId, CarbonPeriod $period, ?int $quota, ?int $releaseDays = null): void
    {
        // Редактирование из админки запрещено
    }

    public function open(int $roomId, CarbonPeriod $period): void
    {
        // Редактирование из админки запрещено
    }

    public function close(int $roomId, CarbonPeriod $period): void
    {
        // Редактирование из админки запрещено
    }

    public function resetRoomQuota(int $roomId, CarbonPeriod $period): void
    {
        // Редактирование из админки запрещено
    }

    public function book(int $bookingId, int $roomId, CarbonPeriod $period, int $count): void
    {
        // Тут приходит запрос от тревелайна и мы обновляем квоты. Внутреннее обновление не требуется.
    }

    public function reserve(int $bookingId, int $roomId, CarbonPeriod $period, int $count): void
    {
        // Сначала мы резервируем квоты (уменьшаем count_available) в БД. После этого бронь подтверждается автоматически и TL присылает актуальные квоты.
        app(ReserveQuotas::class)->execute($bookingId, $roomId, $period, $count);
    }

    public function cancelBooking(int $bookingId): void
    {
        app(CancelBookingQuotas::class)->execute($bookingId);
    }

    public function hasAvailable(int $roomId, CarbonPeriod $period, int $count): bool
    {
        return app(CheckHasAvailable::class)->execute($roomId, $period, $count);
    }
}
