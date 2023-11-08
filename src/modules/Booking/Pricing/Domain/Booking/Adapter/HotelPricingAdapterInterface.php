<?php

namespace Module\Booking\Pricing\Domain\Booking\Adapter;

use Module\Hotel\Pricing\Application\Dto\CalculatedHotelRoomsPricesDto;
use Module\Hotel\Pricing\Application\RequestDto\CalculateHotelPriceRequestDto;

interface HotelPricingAdapterInterface
{
    public function calculate(CalculateHotelPriceRequestDto $requestDto): CalculatedHotelRoomsPricesDto;
}