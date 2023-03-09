<?php

namespace Module\Integration\Traveline\Application\Service;

use Module\Integration\Traveline\Domain\Adapter\HotelAdapterInterface;
use Module\Integration\Traveline\Domain\Api\Request\Update;
use Module\Integration\Traveline\Domain\Api\Response\Error\AbstractTravelineError;
use Module\Integration\Traveline\Domain\Api\Response\Error\InvalidCurrencyCode;
use Module\Integration\Traveline\Domain\Exception\HotelNotConnectedException;
use Module\Integration\Traveline\Domain\Repository\HotelRepositoryInterface;
use Module\Integration\Traveline\Domain\Service\HotelRoomCodeGeneratorInterface;

class QuotaAndPriceUpdater
{
    /** @var array<AbstractTravelineError|null> $errors */
    private array $errors = [];

    public function __construct(
        private readonly HotelAdapterInterface           $adapter,
        private readonly HotelRepositoryInterface        $hotelRepository,
        private readonly HotelRoomCodeGeneratorInterface $codeGenerator,
        private readonly bool                            $isPricesForResidents = false
    ) {}

    /**
     * @param int $hotelId
     * @param array $updates
     * @return AbstractTravelineError[]|null[]
     * @throws HotelNotConnectedException
     */
    public function updateQuotasAndPlans(int $hotelId, array $updates): array
    {
        $isHotelIntegrationEnabled = $this->hotelRepository->isHotelIntegrationEnabled($hotelId);
        if (!$isHotelIntegrationEnabled) {
            throw new HotelNotConnectedException();
        }
        $updateRequests = Update::collectionFromArray($updates, $this->codeGenerator);
        foreach ($updateRequests as $updateRequest) {
            $this->handleRequest($updateRequest);
        }
        return $this->errors;
    }

    private function handleRequest(Update $updateRequest): void
    {
        if ($updateRequest->hasQuota()) {
            $this->adapter->updateRoomQuota(
                $updateRequest->getDatePeriod(),
                $updateRequest->roomTypeId,
                $updateRequest->quota
            );
        }

        if ($updateRequest->hasPrices()) {
            $isSupportedCurrency = $updateRequest->currencyCode !== env('DEFAULT_CURRENCY_CODE');
            if ($isSupportedCurrency) {
                $this->updatePrices($updateRequest);
            } else {
                $this->errors[] = new InvalidCurrencyCode();
            }
        }

        if ($updateRequest->isClosed()) {
            $this->adapter->closeRoomRate(
                $updateRequest->getDatePeriod(),
                $updateRequest->roomTypeId,
                $updateRequest->ratePlanId
            );
        }

        if ($updateRequest->isOpened()) {
            $this->adapter->openRoomRate(
                $updateRequest->getDatePeriod(),
                $updateRequest->roomTypeId,
                $updateRequest->ratePlanId
            );
        }
    }

    private function updatePrices(Update $updateRequest): mixed
    {
        foreach ($updateRequest->prices as $price) {
            $this->adapter->updateRoomPrice(
                $updateRequest->getDatePeriod(),
                $price->roomId,
                $updateRequest->ratePlanId,
                $price->guestsNumber,
                $this->isPricesForResidents,
                $updateRequest->currencyCode,
                $price->price,
            );
        }
        //@todo для тревелайна апдейт цен по нескольким дням - это один запрос, для нас несколько. Подумать как возвращать массив ответов на один запрос. (скорее всего не пригодтися)
        return null;
    }

}
