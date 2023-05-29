<?php

namespace Module\Hotel\Application\Query\Quota;

use Module\Hotel\Application\Dto\QuotaDto;
use Module\Hotel\Domain\Repository\RoomQuotaRepositoryInterface;
use Sdk\Module\Contracts\Bus\QueryHandlerInterface;
use Sdk\Module\Contracts\Bus\QueryInterface;

class GetSoldHandler implements QueryHandlerInterface
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
        $quotas = $this->repository->getSold($query->hotelId, $query->period, $query->roomId);
        return QuotaDto::collection($quotas)->all();
    }
}
