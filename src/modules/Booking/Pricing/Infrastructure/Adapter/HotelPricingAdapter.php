<?php

namespace Module\Booking\Pricing\Infrastructure\Adapter;

use Module\Booking\Pricing\Domain\Booking\Adapter\HotelPricingAdapterInterface;
use Module\Hotel\Pricing\Application\Dto\CalculatedHotelRoomsPricesDto;
use Module\Hotel\Pricing\Application\RequestDto\CalculateHotelPriceRequestDto;
use Module\Hotel\Pricing\Application\UseCase\CalculateHotelPrice;

class HotelPricingAdapter implements HotelPricingAdapterInterface
{
    public function calculate(CalculateHotelPriceRequestDto $requestDto): CalculatedHotelRoomsPricesDto
    {
        return app(CalculateHotelPrice::class)->execute($requestDto);
    }
}