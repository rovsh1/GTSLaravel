<?php

namespace Module\Hotel\Quotation\Application\Service;

use Carbon\Carbon;
use Module\Hotel\Quotation\Application\Dto\QuotaDto;
use Module\Hotel\Quotation\Domain\Entity\RoomQuota;

class RoomQuotaMapper
{
    /**
     * @param array $quotas
     * @return QuotaDto[]
     */
    public function collectionToDto(array $quotas): array
    {
        return array_map(fn($q) => $this->toDto($q), $quotas);
    }

    public function toDto(RoomQuota $quota): QuotaDto
    {
        return new QuotaDto(
            id: $quota->id(),
            roomId: $quota->roomId(),
            date: new Carbon($quota->date()),
            status: $quota->status(),
            releaseDays: $quota->releaseDays(),
            countTotal: $quota->countTotal(),
            countAvailable: $quota->countAvailable(),
            countBooked: $quota->countBooked(),
            countReserved: $quota->countReserved()
        );
    }
}
