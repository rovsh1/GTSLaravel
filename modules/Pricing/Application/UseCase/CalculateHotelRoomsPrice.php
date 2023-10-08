<?php

declare(strict_types=1);

namespace Module\Pricing\Application\UseCase;

use Module\Pricing\Application\Dto\RoomCalculationParamsDto;
use Module\Pricing\Application\Dto\RoomCalculationResultDto;
use Module\Pricing\Application\Dto\RoomDayCalculationResultDto;
use Module\Pricing\Application\RequestDto\CalculateHotelRoomsPriceRequestDto;
use Module\Pricing\Application\ResponseDto\CalculateHotelRoomsPriceResponseDto;
use Module\Pricing\Domain\Hotel\Hotel;
use Module\Pricing\Domain\Hotel\Repository\HotelRepositoryInterface;
use Module\Pricing\Domain\Hotel\Service\BaseDayValueFinder;
use Module\Pricing\Domain\Hotel\Service\RoomDayPriceCalculatorFormula;
use Module\Pricing\Domain\Hotel\ValueObject\HotelId;
use Module\Pricing\Domain\Hotel\ValueObject\RoomId;
use Module\Pricing\Domain\Markup\Service\HotelMarkupFinderInterface;
use Module\Pricing\Domain\Shared\ValueObject\ClientId;
use Module\Shared\Contracts\Adapter\CurrencyRateAdapterInterface;
use Module\Shared\Enum\CurrencyEnum;
use Module\Shared\ValueObject\MarkupValue;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class CalculateHotelRoomsPrice implements UseCaseInterface
{
    public function __construct(
        private readonly HotelRepositoryInterface $hotelRepository,
        private readonly HotelMarkupFinderInterface $hotelMarkupFinder,
        private readonly BaseDayValueFinder $hotelRoomPriceFinder,
        private readonly CurrencyRateAdapterInterface $currencyRateAdapter
    ) {
    }

    public function execute(CalculateHotelRoomsPriceRequestDto $request): CalculateHotelRoomsPriceResponseDto
    {
        $hotel = $this->hotelRepository->findOrFail(new HotelId($request->hotelId));

        $total = 0.0;
        $roomItems = [];
        foreach ($request->rooms as $room) {
            $roomItems[] = $dto = $this->calculateRoomPrice($hotel, $room, $request);
            $total += $dto->price;
        }

        return new CalculateHotelRoomsPriceResponseDto($total, $request->outCurrency ?? $hotel->currency(), $roomItems);
    }

    private function calculateRoomPrice(
        Hotel $hotel,
        RoomCalculationParamsDto $room,
        CalculateHotelRoomsPriceRequestDto $request
    ): RoomCalculationResultDto {
        $roomId = new RoomId($room->roomId);
        $clientMarkup = $request->clientId ? $this->hotelMarkupFinder->findByRoomId(
            clientId: new ClientId($request->clientId),
            roomId: $roomId
        ) : null;

        $total = 0.0;
        $dayResults = [];
        foreach ($request->period as $date) {
            $calculation = new RoomDayPriceCalculatorFormula();

            if ($room->manualDayPrice) {
                $calculation->setBaseManually($room->manualDayPrice);
            } else {
                $baseValue = $this->getPreparedBaseValue(
                    hotel: $hotel,
                    roomId: $roomId,
                    rateId: $room->rateId,
                    isResident: $room->isResident,
                    guestsCount: $room->guestsCount,
                    date: $date,
                    outCurrency: $request->outCurrency
                );
                $calculation->calculateBase(
                    baseValue: $baseValue,
                    vat: $hotel->vat()->value(),
                    touristTax: $hotel->touristTax($room->isResident)->value(),
                    guestCount: $room->guestsCount
                );
            }

            if ($clientMarkup) {
                $calculation->applyClientMarkup($clientMarkup);
            }

            if ($room->earlyCheckinPercent) {
                $calculation->applyEarlyCheckinMarkup(MarkupValue::createPercent($room->earlyCheckinPercent));
            }

            if ($room->lateCheckoutPercent) {
                $calculation->applyLateCheckoutMarkup(MarkupValue::createPercent($room->lateCheckoutPercent));
            }

            $dto = $calculation->result();
            dump($dto->formula);
            $total += $dto->value;
            $dayResults[] = new RoomDayCalculationResultDto(
                date: $date,
                value: $dto->value,
                formula: $dto->formula
            );
        }

        return new RoomCalculationResultDto($room->roomId, $total, $dayResults);
    }

    private function getPreparedBaseValue(
        Hotel $hotel,
        RoomId $roomId,
        int $rateId,
        bool $isResident,
        int $guestsCount,
        \DateTimeInterface $date,
        ?CurrencyEnum $outCurrency = null
    ): float {
        $Po = $this->hotelRoomPriceFinder->findOrFail(
            roomId: $roomId,
            rateId: $rateId,
            isResident: $isResident,
            guestsCount: $guestsCount,
            date: $date
        );

        return $outCurrency ? $this->currencyRateAdapter->convertNetRate(
            price: $Po,
            currencyFrom: $hotel->currency(),
            currencyTo: $outCurrency,
            country: $hotel->countryCode(),
            date: $date
        ) : $Po;
    }
}
