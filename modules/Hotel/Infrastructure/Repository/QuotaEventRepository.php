<?php

namespace Module\Hotel\Infrastructure\Repository;

use Carbon\CarbonPeriod;
use Module\Hotel\Domain\Repository\QuotaEventRepositoryInterface;
use Module\Hotel\Domain\ValueObject\QuotaChangeTypeEnum;

class QuotaEventRepository implements QuotaEventRepositoryInterface
{
    public function registerChanges(
        int $roomId,
        CarbonPeriod $period,
        QuotaChangeTypeEnum $changeType,
        int $count,
        array $context
    ): void {
        if ($changeType === QuotaChangeTypeEnum::RESERVE_BY_BOOKING) {
            //добавить событие о резервации RESERVE_BY_BOOKING (-count)
        }

        if ($changeType === QuotaChangeTypeEnum::BOOK_BY_BOOKING) {
            //добавить событие о списании BOOK_BY_BOOKING (-count)
            //добавить событие о добавлении резерва RESERVE_BY_BOOKING (+count)
        }

        if ($changeType === QuotaChangeTypeEnum::CANCEL_RESERVE_BY_BOOKING) {
            //добавить событие о добавлении резерва RESERVE_BY_BOOKING (+count)
        }

        if ($changeType === QuotaChangeTypeEnum::CANCEL_BOOK_BY_BOOKING) {
            //добавить событие о добавлении резерва BOOK_BY_BOOKING (+count)
        }
    }

    public function resetRoomQuota(int $roomId, CarbonPeriod $period): void
    {
        // TODO: Implement resetRoomQuota() method.
    }

}
