<?php

namespace Module\Integration\Traveline\Application\Service;

use Module\Integration\Traveline\Domain\Adapter\HotelAdapterInterface;
use Module\Integration\Traveline\Domain\Api\Request\Update;
use Module\Integration\Traveline\Domain\Api\Response\Error\InvalidCurrencyCode;
use Module\Integration\Traveline\Domain\Api\Response\Error\InvalidRateAccomodation;
use Module\Integration\Traveline\Domain\Api\Response\Error\InvalidRatePlan;
use Module\Integration\Traveline\Domain\Api\Response\Error\InvalidRoomType;
use Module\Integration\Traveline\Domain\Api\Response\Error\TravelineResponseErrorInterface;
use Module\Integration\Traveline\Domain\Entity\ConfigInterface;
use Module\Integration\Traveline\Domain\Exception\HotelNotConnectedException;
use Module\Integration\Traveline\Domain\Exception\InvalidHotelRoomCode;
use Module\Integration\Traveline\Domain\Repository\HotelRepositoryInterface;
use Module\Integration\Traveline\Domain\Service\HotelRoomCodeGeneratorInterface;

class QuotaAndPriceUpdater
{
    /** @var TravelineResponseErrorInterface[] $errors */
    private array $errors = [];

    public function __construct(
        private readonly HotelAdapterInterface $adapter,
        private readonly HotelRepositoryInterface $hotelRepository,
        private readonly HotelRoomCodeGeneratorInterface $codeGenerator,
        private readonly ConfigInterface $config,
        private readonly bool $isPricesForResidents = false,
    ) {}

    /**
     * @param int $hotelId
     * @param array $updates
     * @return TravelineResponseErrorInterface[]
     * @throws HotelNotConnectedException
     */
    public function updateQuotasAndPlans(int $hotelId, array $updates): array
    {
        $isHotelIntegrationEnabled = $this->hotelRepository->isHotelIntegrationEnabled($hotelId);
        if (!$isHotelIntegrationEnabled) {
            throw new HotelNotConnectedException();
        }

        try {
            $updateRequests = Update::collectionFromArray($updates, $this->codeGenerator);
        } catch (InvalidHotelRoomCode $e) {
            $this->errors[] = new InvalidRateAccomodation();
            return $this->errors;
        }

        foreach ($updateRequests as $updateRequest) {
            try {
                $this->handleRequest($updateRequest);
            } catch (\Throwable $e) {
//                if (!$e->getPrevious() instanceof DomainEntityExceptionInterface) {
//                    throw $e;
//                }
//                $this->errors[] = $this->convertExternalDomainCodeToApiError($e->getPrevious()->domainCode());
            }
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
            if ($this->config->isCurrencySupported($updateRequest->currencyCode)) {
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

    private function updatePrices(Update $updateRequest): void
    {
        foreach ($updateRequest->prices as $price) {
            if ($price->price <= 0) {
                continue;
            }
            $this->adapter->updateRoomPrice(
                $updateRequest->getDatePeriod(),
                $price->roomId,
                $updateRequest->ratePlanId,
                $price->guestsCount,
                $this->isPricesForResidents,
                $updateRequest->currencyCode,
                $price->price,
            );
        }
    }

//    private function convertExternalDomainCodeToApiError(ErrorCodeEnum $domainCode): TravelineResponseErrorInterface
//    {
//        return match ($domainCode) {
//            ErrorCodeEnum::ROOM_NOT_FOUND => new InvalidRoomType(),
//            ErrorCodeEnum::PRICE_RATE_NOT_FOUND => new InvalidRatePlan(),
//            ErrorCodeEnum::UNSUPPORTED_ROOM_GUESTS_NUMBER => new InvalidRateAccomodation(),
//        };
//    }

}
