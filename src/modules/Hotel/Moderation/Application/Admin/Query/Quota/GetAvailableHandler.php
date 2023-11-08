<?php

namespace Module\Hotel\Moderation\Application\Admin\Query\Quota;

use Module\Hotel\Moderation\Application\Admin\Response\QuotaDto;
use Module\Hotel\Moderation\Domain\Hotel\Repository\RoomQuotaRepositoryInterface;
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
