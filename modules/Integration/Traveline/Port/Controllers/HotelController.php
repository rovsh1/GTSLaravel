<?php

namespace Module\Integration\Traveline\Port\Controllers;

use Custom\Framework\Port\Request;
use Module\Integration\Traveline\Application\Dto\HotelDto;
use Module\Integration\Traveline\Application\Service\HotelFinder;
use Module\Integration\Traveline\Domain\Api\Service\QuotaAndPriceUpdater;

class HotelController
{
    public function __construct(
        private HotelFinder          $hotelFinder,
        private QuotaAndPriceUpdater $quotaUpdaterService
    ) {}

    public function update(Request $request)
    {
        $request->validate([
            'hotel_id' => 'required|numeric',
            'updates' => 'required|array'
        ]);

        $domainResponse = $this->quotaUpdaterService->updateQuotasAndPlans($request->hotel_id, $request->updates);

        //@todo конвертация в DTO
        return $domainResponse;
    }

    public function getRoomsAndRatePlans(Request $request): HotelDto
    {
        $request->validate([
            'hotel_id' => 'required|numeric',
        ]);

        return $this->hotelFinder->getHotelRoomsAndRatePlans($request->hotel_id);
    }

}
