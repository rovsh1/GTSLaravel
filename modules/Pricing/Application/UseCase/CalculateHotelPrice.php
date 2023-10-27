<?php

declare(strict_types=1);

namespace Module\Pricing\Application\UseCase;

use Module\Pricing\Application\Dto\CalculatedHotelRoomsPricesDto;
use Module\Pricing\Application\Dto\RoomCalculationParamsDto;
use Module\Pricing\Application\Dto\RoomCalculationResultDto;
use Module\Pricing\Application\Dto\RoomDayCalculationResultDto;
use Module\Pricing\Application\RequestDto\CalculateHotelPriceRequestDto;
use Module\Pricing\Domain\Hotel\Hotel;
use Module\Pricing\Domain\Hotel\Repository\HotelRepositoryInterface;
use Module\Pricing\Domain\Hotel\Service\RoomDayPriceCalculatorFormula;
use Module\Pricing\Domain\Hotel\ValueObject\HotelId;
use Module\Pricing\Domain\Hotel\ValueObject\RoomId;
use Module\Pricing\Domain\Markup\Service\HotelMarkupFinderInterface;
use Module\Pricing\Domain\Shared\ValueObject\ClientId;
use Module\Pricing\Infrastructure\Service\HotelRoomBaseDayValueFinder;
use Module\Shared\Contracts\Adapter\CurrencyRateAdapterInterface;
use Module\Shared\Contracts\Service\ApplicationConstantsInterface;
use Module\Shared\Enum\CurrencyEnum;
use Module\Shared\ValueObject\MarkupValue;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class CalculateHotelPrice implements UseCaseInterface
{
    public function __construct(
        private readonly HotelRepositoryInterface $hotelRepository,
        private readonly HotelMarkupFinderInterface $hotelMarkupFinder,
        private readonly HotelRoomBaseDayValueFinder $hotelRoomPriceFinder,
        private readonly CurrencyRateAdapterInterface $currencyRateAdapter,
        private readonly ApplicationConstantsInterface $constants,
    ) {}

    public function execute(CalculateHotelPriceRequestDto $request): CalculatedHotelRoomsPricesDto
    {
        $hotel = $this->hotelRepository->findOrFail(new HotelId($request->hotelId));

        $total = 0.0;
        $roomItems = [];
        foreach ($request->rooms as $room) {
            $roomItems[] = $dto = $this->calculateRoomPrice($hotel, $room, $request);
            $total += $dto->price;
        }

        return new CalculatedHotelRoomsPricesDto($total, $request->outCurrency ?? $hotel->currency(), $roomItems);
    }

    private function calculateRoomPrice(
        Hotel $hotel,
        RoomCalculationParamsDto $room,
        CalculateHotelPriceRequestDto $request
    ): RoomCalculationResultDto {
        if ($room->guestsCount === 0) {
            return RoomCalculationResultDto::createZero($room->accommodationId, $request->period);
        }

        $roomId = new RoomId($room->roomId);
        $clientMarkup = $request->clientId ? $this->hotelMarkupFinder->findByRoomId(
            clientId: new ClientId($request->clientId),
            roomId: $roomId
        ) : null;

        $total = 0.0;
        $dayResults = [];
        //@hack т.к. ночей всегда на 1 меньше, не включаем в стоимость последний день периода
        foreach ($request->period->excludeEndDate() as $date) {
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
                    basicCalculatedValue: $this->getPreparedBasicCalculatedValue($hotel, $date, $request->outCurrency),
                    baseRoomValue: $baseValue,
                    vat: $hotel->vat()->value(),
                    touristTax: $hotel->touristTax($room->isResident)->value(),
                    guestCount: $room->guestsCount
                );
            }

            if ($clientMarkup) {
                $calculation->applyClientMarkup($clientMarkup);
            }

            if ($room->earlyCheckinPercent && $request->period->getStartDate()->equalTo($date)) {
                $calculation->applyEarlyCheckinMarkup(MarkupValue::createPercent($room->earlyCheckinPercent));
            }

            //@hack т.к. ночей всегда на 1 меньше, применяем поздний выезд к предпоследнему дню периода
            if ($room->lateCheckoutPercent && $request->period->getEndDate()->subDay()->equalTo($date)) {
                $calculation->applyLateCheckoutMarkup(MarkupValue::createPercent($room->lateCheckoutPercent));
            }

            $dto = $calculation->result();
            $total += $dto->value;
            $dayResults[] = new RoomDayCalculationResultDto(
                date: $date,
                value: $dto->value,
                formula: $dto->formula
            );
        }

        return new RoomCalculationResultDto($room->accommodationId, $total, $dayResults);
    }

    private function getPreparedBasicCalculatedValue(Hotel $hotel, \DateTimeInterface $date, CurrencyEnum $outCurrency): float {
        return $this->currencyRateAdapter->convertNetRate(
            price: $this->constants->basicCalculatedValue(),
            currencyFrom: CurrencyEnum::UZS,
            currencyTo: $outCurrency,
            country: $hotel->countryCode(),
            date: $date
        );
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
