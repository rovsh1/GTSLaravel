<?php

declare(strict_types=1);

namespace Pkg\Supplier\Traveline\UseCase;

use Pkg\Supplier\Traveline\Dto\Request\Update;
use Pkg\Supplier\Traveline\Dto\Response\Error\TravelineResponseErrorInterface;
use Pkg\Supplier\Traveline\Exception\HotelNotConnectedException;
use Pkg\Supplier\Traveline\Service\QuotaAndPriceUpdater;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class UpdateQuotasAndPlans implements UseCaseInterface
{
    public function __construct(
        private readonly QuotaAndPriceUpdater $quotaUpdaterService
    ) {}

    /**
     * @param int $hotelId
     * @param Update[] $updates
     * @return TravelineResponseErrorInterface[]
     * @throws HotelNotConnectedException
     */
    public function execute(int $hotelId, array $updates): array
    {
        return $this->quotaUpdaterService->updateQuotasAndPlans($hotelId, $updates);
    }
}
