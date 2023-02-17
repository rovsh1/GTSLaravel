<?php

namespace GTS\Integration\Traveline\Infrastructure\Facade;

use GTS\Integration\Traveline\Application\Dto\HotelDto;
use GTS\Integration\Traveline\Application\Service\HotelFinder;
use GTS\Integration\Traveline\Domain\Api\Service\QuotaAndPriceUpdater;

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
