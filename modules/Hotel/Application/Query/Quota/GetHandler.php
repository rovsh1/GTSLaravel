<?php

namespace Module\Hotel\Application\Query\Quota;

use Custom\Framework\Contracts\Bus\QueryHandlerInterface;
use Custom\Framework\Contracts\Bus\QueryInterface;
use Module\Hotel\Application\Dto\QuotaDto;
use Module\Hotel\Domain\Repository\RoomQuotaRepositoryInterface;

class GetHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly RoomQuotaRepositoryInterface $repository
    ) {}

    /**
     * @param Get $query
     * @return QuotaDto[]
     */
    public function handle(QueryInterface|Get $query): array
    {
        $quotas = $this->repository->get($query->hotelId, $query->period, $query->roomId);
        return QuotaDto::collection($quotas)->all();
    }
}
