<?php

declare(strict_types=1);

namespace Module\Hotel\Quotation\Application\Service\Supplier;

use Carbon\CarbonPeriod;
use Module\Hotel\Quotation\Application\Dto\QuotaDto;
use Module\Hotel\Quotation\Application\Service\SupplierQuotaAvailabilityFetcherInterface;
use Module\Hotel\Quotation\Application\Service\SupplierQuotaBookerInterface;
use Module\Hotel\Quotation\Application\Service\SupplierQuotaFetcherInterface;
use Module\Hotel\Quotation\Application\Service\SupplierQuotaUpdaterInterface;
use Shared\Contracts\Adapter\TravelineAdapterInterface;

class Traveline implements SupplierQuotaFetcherInterface,
                           SupplierQuotaUpdaterInterface,
                           SupplierQuotaBookerInterface,
                           SupplierQuotaAvailabilityFetcherInterface
{
    public function __construct(
        private readonly TravelineAdapterInterface $travelineAdapter,
    ) {}

    public function getAvailableCount(int $roomId, CarbonPeriod $period): int
    {
        return $this->travelineAdapter->getAvailableCount($roomId, $period);
    }

    public function getAll(int $hotelId, CarbonPeriod $period, ?int $roomId): array
    {
        return $this->travelineAdapter->getAllQuotas($hotelId, $period, $roomId);
    }

    public function getAvailable(int $hotelId, CarbonPeriod $period, ?int $roomId): array
    {
        return $this->travelineAdapter->getAvailableQuotas($hotelId, $period, $roomId);
    }

    public function getClosed(int $hotelId, CarbonPeriod $period, ?int $roomId): array
    {
        return $this->travelineAdapter->getClosedQuotas($hotelId, $period, $roomId);
    }

    public function getSold(int $hotelId, CarbonPeriod $period, ?int $roomId): array
    {
        return $this->travelineAdapter->getSoldQuotas($hotelId, $period, $roomId);
    }

    /**
     * @param CarbonPeriod $period
     * @param int[] $cityIds
     * @param int[] $hotelIds
     * @param int[] $roomIds
     * @return QuotaDto[]
     */
    public function getQuotasAvailability(
        CarbonPeriod $period,
        array $cityIds = [],
        array $hotelIds = [],
        array $roomIds = [],
        array $roomTypeIds = []
    ): array {
        return $this->travelineAdapter->getQuotasAvailability($period, $cityIds, $hotelIds, $roomIds, $roomTypeIds);
    }

    public function update(int $roomId, CarbonPeriod $period, ?int $quota, ?int $releaseDays = null): void
    {
        $this->travelineAdapter->update($roomId, $period, $quota, $releaseDays);
    }

    public function open(int $roomId, CarbonPeriod $period): void
    {
        $this->travelineAdapter->open($roomId, $period);
    }

    public function close(int $roomId, CarbonPeriod $period): void
    {
        $this->travelineAdapter->close($roomId, $period);
    }

    public function resetRoomQuota(int $roomId, CarbonPeriod $period): void
    {
        $this->travelineAdapter->resetRoomQuota($roomId, $period);
    }

    public function book(int $bookingId, int $roomId, CarbonPeriod $period, int $count): void
    {
        $this->travelineAdapter->book($bookingId, $roomId, $period, $count);
    }

    public function reserve(int $bookingId, int $roomId, CarbonPeriod $period, int $count): void
    {
        $this->travelineAdapter->reserve($bookingId, $roomId, $period, $count);
    }

    public function cancelBooking(int $bookingId): void
    {
        $this->travelineAdapter->cancelBooking($bookingId);
    }

    public function hasAvailable(int $roomId, CarbonPeriod $period, int $count): bool
    {
        return $this->travelineAdapter->hasAvailable($roomId, $period, $count);
    }
}
