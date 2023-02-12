<?php

namespace GTS\Integration\Traveline\Domain\Service\Api;

use GTS\Integration\Traveline\Domain\Adapter\HotelAdapterInterface;
use GTS\Integration\Traveline\Domain\Api\Request\Update;
use GTS\Integration\Traveline\Domain\Exception\HotelNotConnectedException;
use GTS\Integration\Traveline\Domain\Repository\HotelRepositoryInterface;

class UpdaterService
{
    public function __construct(
        private HotelAdapterInterface    $adapter,
        private HotelRepositoryInterface $hotelRepository
    ) {}

    public function updateQuotasAndPlans(int $hotelId, array $updates)
    {
        $isHotelIntegrationEnabled = $this->hotelRepository->isHotelIntegrationEnabled($hotelId);
        if (!$isHotelIntegrationEnabled) {
            throw new HotelNotConnectedException();
        }
        $updateRequests = Update::collectionFromArray($updates);
        $quotaResponses = [];
        $priceResponses = [];
        $closeResponses = [];
        $openResponses = [];
        foreach ($updateRequests as $updateRequest) {
            if ($updateRequest->quota !== null) {
                $quotaResponses[] = $this->adapter->updateRoomQuota($updateRequest->getDatePeriod(), $updateRequest->roomTypeId, $updateRequest->quota);
            }
            if ($updateRequest->prices !== null) {
                foreach ($updateRequest->prices as $price) {
                    $priceResponses[] = $this->adapter->updateRoomRatePrice($updateRequest->getDatePeriod(), $updateRequest->roomTypeId, $updateRequest->ratePlanId, $updateRequest->currencyCode, $price->price);
                }
            }
            if ($updateRequest->closed === false) {
                $closeResponses[] = $this->adapter->openRoomRate($updateRequest->getDatePeriod(), $updateRequest->roomTypeId, $updateRequest->ratePlanId);
            }
            if ($updateRequest->closed === true) {
                $openResponses[] = $this->adapter->closeRoomRate($updateRequest->getDatePeriod(), $updateRequest->roomTypeId, $updateRequest->ratePlanId);
            }
        }

        dd($quotaResponses, $priceResponses, $closeResponses, $openResponses);
    }

}
