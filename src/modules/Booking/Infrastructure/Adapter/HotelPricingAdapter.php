<?php

namespace Module\Booking\Infrastructure\Adapter;

use Module\Booking\Domain\Booking\Adapter\HotelPricingAdapterInterface;
use Module\Pricing\Application\Dto\CalculatedHotelRoomsPricesDto;
use Module\Pricing\Application\RequestDto\CalculateHotelPriceRequestDto;
use Module\Pricing\Application\UseCase\CalculateHotelPrice;

class HotelPricingAdapter implements HotelPricingAdapterInterface
{
    public function calculate(CalculateHotelPriceRequestDto $requestDto): CalculatedHotelRoomsPricesDto
    {
        return app(CalculateHotelPrice::class)->execute($requestDto);
    }
}