<?php

namespace Module\Hotel\Application\Query;

use Custom\Framework\Contracts\Bus\QueryHandlerInterface;
use Custom\Framework\Contracts\Bus\QueryInterface;
use Module\Hotel\Application\Dto\QuotaDto;
use Module\Hotel\Domain\Repository\RoomQuotaRepositoryInterface;

class GetRoomQuotaHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly RoomQuotaRepositoryInterface $repository
    ) {}

    /**
     * @param GetRoomQuota $query
     * @return QuotaDto[]
     */
    public function handle(QueryInterface|GetRoomQuota $query): array
    {
        $quotas = $this->repository->get($query->roomId, $query->period);
        return QuotaDto::collection($quotas)->all();
    }
}
