<?php

namespace Module\Integration\Traveline\Infrastructure\Facade;

use Module\Integration\Traveline\Application\Dto\HotelDto;
use Module\Integration\Traveline\Application\Service\HotelFinder;
use Module\Integration\Traveline\Domain\Api\Service\QuotaAndPriceUpdater;

class HotelFacade implements HotelFacadeInterface
{
    public function __construct(
        private HotelFinder  $hotelFinder,
        private QuotaAndPriceUpdater $quotaUpdaterService
    ) {}

    public function getRoomsAndRatePlans(int $hotelId): HotelDto
    {
        return $this->hotelFinder->getHotelRoomsAndRatePlans($hotelId);
    }

    public function updateQuotasAndPlans(int $hotelId, array $updates)
    {
        $domainResponse = $this->quotaUpdaterService->updateQuotasAndPlans($hotelId, $updates);

        //@todo конвертация в DTO
        return $domainResponse;
    }
}
