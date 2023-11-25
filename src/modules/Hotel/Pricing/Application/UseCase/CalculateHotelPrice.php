<?php

declare(strict_types=1);

namespace Module\Hotel\Pricing\Application\UseCase;

use Module\Client\Moderation\Application\Admin\UseCase\ConvertClientCurrencyRate;
use Module\Hotel\Pricing\Application\Dto\CalculatedHotelRoomsPricesDto;
use Module\Hotel\Pricing\Application\Dto\RoomCalculationParamsDto;
use Module\Hotel\Pricing\Application\Dto\RoomCalculationResultDto;
use Module\Hotel\Pricing\Application\Dto\RoomDayCalculationResultDto;
use Module\Hotel\Pricing\Application\Exception\RoomPriceNotFoundException;
use Module\Hotel\Pricing\Application\RequestDto\CalculateHotelPriceRequestDto;
use Module\Hotel\Pricing\Domain\Hotel\Exception\NotFoundHotelRoomPrice;
use Module\Hotel\Pricing\Domain\Hotel\Hotel;
use Module\Hotel\Pricing\Domain\Hotel\Repository\HotelRepositoryInterface;
use Module\Hotel\Pricing\Domain\Hotel\Service\RoomDayPriceCalculatorFormula;
use Module\Hotel\Pricing\Domain\Hotel\ValueObject\HotelId;
use Module\Hotel\Pricing\Domain\Hotel\ValueObject\RoomId;
use Module\Hotel\Pricing\Domain\Markup\Service\HotelMarkupFinderInterface;
use Module\Hotel\Pricing\Domain\Shared\ValueObject\ClientId;
use Module\Hotel\Pricing\Infrastructure\Service\HotelRoomBaseDayValueFinder;
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
    ) {
    }

    public function execute(CalculateHotelPriceRequestDto $request): CalculatedHotelRoomsPricesDto
    {
        $hotel = $this->hotelRepository->findOrFail(new HotelId($request->hotelId));

        $total = 0.0;
        $roomItems = [];
        foreach ($request->rooms as $room) {
            try {
                $roomItems[] = $dto = $this->calculateRoomPrice($hotel, $room, $request);
            } catch (NotFoundHotelRoomPrice $e) {
                throw new RoomPriceNotFoundException($e);
            }

            $total += $dto->price;
        }

        return new CalculatedHotelRoomsPricesDto($total, $request->outCurrency ?? $hotel->currency(), $roomItems);
    }

    private function calculateRoomPrice(
        Hotel $hotel,
        RoomCalculationParamsDto $room,
        CalculateHotelPriceRequestDto $request,
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
                    outCurrency: $request->outCurrency,
                    clientId: $request->clientId,
                );

                $calculation->calculateBase(
                    basicCalculatedValue: $this->getPreparedBasicCalculatedValue($hotel, $request->clientId, $date, $request->outCurrency),
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

    private function getPreparedBasicCalculatedValue(
        Hotel $hotel,
        ?int $clientId,
        \DateTimeInterface $date,
        CurrencyEnum $outCurrency
    ): float {
        return $this->convertCurrencyRate(
            hotelId: $hotel->id()->value(),
            price: $this->constants->basicCalculatedValue(),
            currencyFrom: CurrencyEnum::UZS,
            currencyTo: $outCurrency,
            date: $date,
            clientId: $clientId,
            country: $hotel->countryCode()
        );
    }

    private function getPreparedBaseValue(
        Hotel $hotel,
        RoomId $roomId,
        int $rateId,
        bool $isResident,
        int $guestsCount,
        \DateTimeInterface $date,
        ?CurrencyEnum $outCurrency = null,
        ?int $clientId = null
    ): float {
        $Po = $this->hotelRoomPriceFinder->findOrFail(
            roomId: $roomId,
            rateId: $rateId,
            isResident: $isResident,
            guestsCount: $guestsCount,
            date: $date
        );

        if ($outCurrency === null) {
            return $Po;
        }

        return $this->convertCurrencyRate(
            hotelId: $hotel->id()->value(),
            price: $Po,
            currencyFrom: $hotel->currency(),
            currencyTo: $outCurrency,
            date: $date,
            clientId: $clientId,
            country: $hotel->countryCode(),
        );
    }

    private function convertCurrencyRate(
        int $hotelId,
        float $price,
        CurrencyEnum $currencyFrom,
        CurrencyEnum $currencyTo,
        \DateTimeInterface $date,
        ?int $clientId,
        ?string $country = null
    ): float {
        if ($clientId === null) {
            return $this->currencyRateAdapter->convertNetRate($price, $currencyFrom, $currencyTo, $country, $date);
        }

        return app(ConvertClientCurrencyRate::class)->execute(
            clientId: $clientId,
            hotelId: $hotelId,
            price: $price,
            currencyFrom: $currencyFrom,
            currencyTo: $currencyTo,
            date: $date,
            country: $country
        );
    }
}
