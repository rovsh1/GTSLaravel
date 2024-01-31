<?php

namespace Pkg\Supplier\Traveline\Service;

use Module\Hotel\Moderation\Domain\Hotel\Exception\Room\PriceRateNotFound;
use Pkg\Supplier\Traveline\Adapters\HotelAdapter;
use Pkg\Supplier\Traveline\Dto\Request\Update;
use Pkg\Supplier\Traveline\Exception\HotelNotConnectedException;
use Pkg\Supplier\Traveline\Exception\RoomNotFoundException;
use Pkg\Supplier\Traveline\Http\Response\Error\InvalidCurrencyCode;
use Pkg\Supplier\Traveline\Http\Response\Error\InvalidRatePlan;
use Pkg\Supplier\Traveline\Http\Response\Error\InvalidRoomType;
use Pkg\Supplier\Traveline\Http\Response\Error\TravelineResponseErrorInterface;
use Pkg\Supplier\Traveline\Repository\HotelRepository;
use Pkg\Supplier\Traveline\Repository\RoomQuotaRepository;

class QuotaAndPriceUpdater
{
    /** @var TravelineResponseErrorInterface[] $errors */
    private array $errors = [];

    public function __construct(
        private readonly HotelAdapter $adapter,
        private readonly HotelRepository $hotelRepository,
        private readonly RoomQuotaRepository $quotaRepository,
        private readonly array $supportedCurrencies,
        private readonly bool $isPricesForResidents = false,
    ) {}

    /**
     * @param int $hotelId
     * @param Update[] $updates
     * @return TravelineResponseErrorInterface[]
     * @throws HotelNotConnectedException
     */
    public function updateQuotasAndPlans(int $hotelId, array $updates): array
    {
        $isHotelIntegrationEnabled = $this->hotelRepository->isHotelIntegrationEnabled($hotelId);
        if (!$isHotelIntegrationEnabled) {
            throw new HotelNotConnectedException();
        }

        foreach ($updates as $updateRequest) {
            try {
                $this->handleRequest($updateRequest);
            } catch (\Throwable $e) {
                $this->handleException($e);

                \Log::error(
                    '[Traveline] Unknown error in update request process',
                    ['request' => $updateRequest, 'error' => $e]
                );
            }
        }

        return $this->errors;
    }

    private function handleRequest(Update $updateRequest): void
    {
        if ($updateRequest->hasQuota()) {
            $this->quotaRepository->updateRoomQuota(
                $updateRequest->roomTypeId,
                $updateRequest->getDatePeriod(),
                $updateRequest->quota
            );
        }

        if ($updateRequest->hasReleaseDays()) {
            $this->quotaRepository->updateRoomReleaseDays(
                $updateRequest->roomTypeId,
                $updateRequest->getDatePeriod(),
                $updateRequest->releaseDays,
            );
        }

        if ($updateRequest->hasPrices()) {
            if ($this->isCurrencySupported($updateRequest->currencyCode)) {
                $this->updatePrices($updateRequest);
            } else {
                \Log::warning(
                    '[Traveline] Received unsupported currency update request',
                    ['request' => $updateRequest, 'config_value' => $this->supportedCurrencies]
                );
                $this->errors[] = new InvalidCurrencyCode();
            }
        }

        if ($updateRequest->isClosed()) {
            $this->quotaRepository->closeRoomQuota($updateRequest->roomTypeId, $updateRequest->getDatePeriod());
        }

        if ($updateRequest->isOpened()) {
            $this->quotaRepository->openRoomQuota($updateRequest->roomTypeId, $updateRequest->getDatePeriod());
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

    private function isCurrencySupported(string $currencyCode): bool
    {
        return in_array($currencyCode, $this->supportedCurrencies);
    }

    private function handleException(\Throwable $exception): void
    {
        if ($exception instanceof RoomNotFoundException) {
            $this->errors[] = new InvalidRoomType();
        } elseif ($exception instanceof PriceRateNotFound) {
            $this->errors[] = new InvalidRatePlan();
        }
    }
}
