<?php

namespace Pkg\Supplier\Traveline\Dto;

use Module\Hotel\Moderation\Application\Dto\PriceRateDto;

final class RatePlanDto
{
    public function __construct(
        public readonly int $ratePlanId,
        public readonly string $ratePlanName,
    ) {}

    public static function fromHotelPriceRate(PriceRateDto $priceRate): self
    {
        return new self($priceRate->id, $priceRate->name);
    }

    /**
     * @param PriceRateDto[] $items
     * @return array
     */
    public static function collection(array $items): array
    {
        return array_map(fn(PriceRateDto $item) => self::fromHotelPriceRate($item), $items);
    }
}
