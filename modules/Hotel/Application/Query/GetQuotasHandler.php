<?php

namespace Module\Hotel\Application\Query;

use Custom\Framework\Contracts\Bus\QueryHandlerInterface;
use Custom\Framework\Contracts\Bus\QueryInterface;
use Module\Hotel\Application\Dto\QuotaDto;
use Module\Hotel\Domain\Repository\RoomQuotaRepositoryInterface;

class GetQuotasHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly RoomQuotaRepositoryInterface $repository
    ) {}

    /**
     * @param GetQuotas $query
     * @return QuotaDto[]
     */
    public function handle(QueryInterface|GetQuotas $query): array
    {
        $quotas = $this->repository->get($query->hotelId, $query->period, $query->roomId, $query->availability);
        return QuotaDto::collection($quotas)->all();
    }
}
