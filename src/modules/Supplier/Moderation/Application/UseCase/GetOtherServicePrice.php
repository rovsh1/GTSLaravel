<?php

declare(strict_types=1);

namespace Module\Supplier\Moderation\Application\UseCase;

use Carbon\CarbonInterface;
use Module\Hotel\Pricing\Application\Dto\PriceDto;
use Module\Hotel\Pricing\Application\Dto\ServicePriceDto;
use Module\Supplier\Moderation\Infrastructure\Models\OtherServicePrice as Model;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Shared\Enum\CurrencyEnum;

class GetOtherServicePrice implements UseCaseInterface
{
    public function execute(
        int $supplierId,
        int $serviceId,
        CurrencyEnum $grossCurrency,
        CarbonInterface $date
    ): ?ServicePriceDto {
        $price = Model::whereSupplierId($supplierId)
            ->whereServiceId($serviceId)
//            ->whereCurrency($supplier->currency()->id())
            ->whereCurrency(CurrencyEnum::UZS)//@hack т.к. сейчас нетто цены только в UZS
            ->whereDate($date)
            ->first();
        if ($price === null) {
            return null;
        }

        return $this->buildDtoFromModel($price, $grossCurrency);
    }

    private function buildDtoFromModel(Model $servicePrice, CurrencyEnum $grossCurrency): ?ServicePriceDto
    {
        $grossPrice = collect($servicePrice->prices_gross)->first(
            fn(array $priceData) => $priceData['currency'] === $grossCurrency->name
        );
        if ($grossPrice === null) {
            return null;
        }

        return new ServicePriceDto(
            supplierPrice: new PriceDto(
                amount: $servicePrice->price_net,
                currency: $servicePrice->currency
            ),
            clientPrice: new PriceDto(
                amount: (float)$grossPrice['amount'],
                currency: $grossCurrency
            ),
        );
    }
}
