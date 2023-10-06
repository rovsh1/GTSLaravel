<?php

declare(strict_types=1);

namespace Module\Pricing\Application\UseCase;

use Module\Pricing\Application\Request\CalculateHotelRoomPriceRequestDto;
use Module\Pricing\Domain\HotelPricing\Service\PriceCalculator\Factory\FormulaVariablesFactory;
use Module\Pricing\Domain\HotelPricing\Service\PriceCalculator\HotelPriceCalculator;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class CalculateNetHotelRoomPrice implements UseCaseInterface
{
    public function __construct(
        private readonly FormulaVariablesFactory $formulaVariablesFactory,
        private readonly HotelPriceCalculator $hotelPriceCalculator,
    ) {}

    public function execute(CalculateHotelRoomPriceRequestDto $request)
    {
        $formulaVariables = $this->formulaVariablesFactory->fromRequest($request);
        $this->hotelPriceCalculator->calculate($formulaVariables);
    }
}
