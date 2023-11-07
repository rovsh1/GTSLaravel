<?php

namespace Module\Booking\Pricing\Domain\Booking\Adapter;

use Module\Pricing\Application\Dto\CalculatedHotelRoomsPricesDto;
use Module\Pricing\Application\RequestDto\CalculateHotelPriceRequestDto;

interface HotelPricingAdapterInterface
{
    public function calculate(CalculateHotelPriceRequestDto $requestDto): CalculatedHotelRoomsPricesDto;
}