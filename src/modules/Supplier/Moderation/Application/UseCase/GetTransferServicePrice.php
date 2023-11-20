<?php

declare(strict_types=1);

namespace Module\Supplier\Moderation\Application\UseCase;

use Carbon\CarbonInterface;
use Module\Hotel\Pricing\Application\Dto\PriceDto;
use Module\Hotel\Pricing\Application\Dto\ServicePriceDto;
use Module\Shared\Enum\CurrencyEnum;
use Module\Supplier\Moderation\Infrastructure\Models\TransferServicePrice as Model;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetTransferServicePrice implements UseCaseInterface
{
    public function execute(
        int $supplierId,
        int $serviceId,
        int $carId,
        CurrencyEnum $grossCurrency,
        CarbonInterface $date
    ): ?ServicePriceDto {
        $price = Model::whereSupplierId($supplierId)
            ->whereServiceId($serviceId)
            ->whereCarId($carId)
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
            fn(array $priceData) => CurrencyEnum::from($priceData['currency']) === $grossCurrency
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
