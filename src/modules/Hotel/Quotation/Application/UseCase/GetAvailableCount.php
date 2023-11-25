<?php

declare(strict_types=1);

namespace Module\Hotel\Quotation\Application\UseCase;

use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Module\Hotel\Quotation\Domain\Repository\QuotaRepositoryInterface;
use Module\Hotel\Quotation\Domain\ValueObject\BookingPeriod;
use Module\Hotel\Quotation\Domain\ValueObject\RoomId;
use Module\Hotel\Quotation\Infrastructure\Model\Quota;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetAvailableCount implements UseCaseInterface
{
    public function __construct(
        private readonly QuotaRepositoryInterface $quotaRepository
    ) {}

    public function execute(int $roomId, CarbonPeriod $period): int
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
            Quota::query()
                ->whereRoomId($roomId)
                ->wherePeriod($period)
                ->withCountColumns(),
            't'
        )->min('t.count_available');
    }
}
