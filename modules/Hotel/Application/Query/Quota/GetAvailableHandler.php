<?php

namespace Module\Hotel\Application\Query\Quota;

use Module\Hotel\Application\Response\QuotaDto;
use Module\Hotel\Domain\Repository\RoomQuotaRepositoryInterface;
use Sdk\Module\Contracts\Bus\QueryHandlerInterface;
use Sdk\Module\Contracts\Bus\QueryInterface;

class GetAvailableHandler implements QueryHandlerInterface
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
        $quotas = $this->repository->getAvailable($query->hotelId, $query->period, $query->roomId);
        return QuotaDto::collection($quotas)->all();
    }
}
