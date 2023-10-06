<?php

declare(strict_types=1);

namespace Module\Pricing\Application\UseCase;

use DateTimeInterface;
use Module\Pricing\Application\Request\CalculateHotelRoomPriceRequestDto;
use Module\Pricing\Domain\Hotel\Hotel;
use Module\Pricing\Domain\Hotel\Repository\HotelRepositoryInterface;
use Module\Pricing\Domain\Hotel\Service\HotelRoomPriceFinder;
use Module\Pricing\Domain\Hotel\UseCase\CalculateRoomDayPrice;
use Module\Pricing\Domain\Hotel\ValueObject\RoomId;
use Module\Pricing\Domain\Markup\UseCase\CalculateHotelRoomMarkup;
use Module\Pricing\Domain\Shared\ValueObject\ClientId;
use Module\Shared\Domain\Adapter\CurrencyRateAdapterInterface;
use Module\Shared\Enum\CurrencyEnum;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class CalculateHotelRoomPrice implements UseCaseInterface
{
    public function __construct(
        private readonly CalculateRoomDayPrice $calculateRoomDayPrice,
        private readonly HotelRepositoryInterface $hotelRepository,
        private readonly HotelRoomPriceFinder $hotelRoomPriceFinder,
        private readonly CalculateHotelRoomMarkup $calculateHotelMarkup,
        private readonly CurrencyRateAdapterInterface $currencyRateAdapter
    ) {
    }

    /**
     * Pr* = Pr + N(Po)
     *
     * @param CalculateHotelRoomPriceRequestDto $request
     * @return float
     * @throws \Exception
     *
     * @see /docs/app/price-calculation.md
     */
    public function execute(CalculateHotelRoomPriceRequestDto $request): float
    {
        $roomId = new RoomId($request->roomId);
        $hotel = $this->hotelRepository->findByRoomId($roomId);

        $Po = $this->getBaseValue($roomId, $request);
        $Po = $this->convertToCurrency($Po, $request->outCurrency, $hotel, $request->date);

        $Pr = $this->calculateRoomDayPrice->execute(
            Po: $Po,
            roomId: $roomId,
            isResident: $request->isResident,
            guestCount: $request->guestsCount
        );

        return $request->withMarkups
            ? $this->calculateWithMarkup($Pr, $roomId, $request->clientId)
            : $Pr;
    }

    private function getBaseValue(RoomId $roomId, CalculateHotelRoomPriceRequestDto $request): float
    {
        $Po = $this->hotelRoomPriceFinder->find(
            roomId: $roomId,
            rateId: $request->rateId,
            isResident: $request->isResident,
            guestsCount: $request->guestsCount,
            date: $request->date
        );

        if (null === $Po) {
            throw new \Exception();
        }

        return $Po;
    }

    private function calculateWithMarkup(float $Pr, RoomId $roomId, int $clientId): float
    {
        $markupValue = $this->calculateHotelMarkup->execute(
            price: $Pr,
            clientId: new ClientId($clientId),
            roomId: $roomId
        );

        return $Pr + $markupValue;
    }

    private function convertToCurrency(
        float $price,
        ?CurrencyEnum $outCurrency,
        Hotel $hotel,
        DateTimeInterface $date
    ): float {
        return $outCurrency ? $this->currencyRateAdapter->convertNetRate(
            price: $price,
            currencyFrom: $hotel->currency(),
            currencyTo: $outCurrency,
            country: $hotel->countryCode(),
            date: $date
        ) : $price;
    }
}
