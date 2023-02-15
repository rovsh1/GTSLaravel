<?php

namespace GTS\Integration\Traveline\Domain\Api\Service;

use GTS\Integration\Traveline\Domain\Adapter\HotelAdapterInterface;
use GTS\Integration\Traveline\Domain\Api\Request\Update;
use GTS\Integration\Traveline\Domain\Exception\HotelNotConnectedException;
use GTS\Integration\Traveline\Domain\Repository\HotelRepositoryInterface;

class QuotaUpdater
{
    private array $responses = [];

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
        foreach ($updateRequests as $updateRequest) {
            $this->handleRequest($updateRequest);
        }

        dd($this->responses);
    }

    private function handleRequest(Update $updateRequest): void
    {
        if ($updateRequest->hasQuota()) {
            $this->responses[] = $this->adapter->updateRoomQuota(
                $updateRequest->getDatePeriod(),
                $updateRequest->roomTypeId,
                $updateRequest->quota
            );
        }

        if ($updateRequest->hasPrices()) {
            foreach ($updateRequest->prices as $price) {
                $this->responses[] = $this->adapter->updateRoomPrice(
                    $updateRequest->getDatePeriod(),
                    $price->roomId,
                    $updateRequest->ratePlanId,
                    $price->guestsNumber,
                    $updateRequest->currencyCode,
                    $price->price
                );
            }
        }

        if ($updateRequest->isClosed()) {
            $this->responses[] = $this->adapter->closeRoomRate(
                $updateRequest->getDatePeriod(),
                $updateRequest->roomTypeId,
                $updateRequest->ratePlanId
            );
        }

        if ($updateRequest->isOpened()) {
            $this->responses[] = $this->adapter->openRoomRate(
                $updateRequest->getDatePeriod(),
                $updateRequest->roomTypeId,
                $updateRequest->ratePlanId
            );
        }
    }

}
