<?php

declare(strict_types=1);

namespace Module\Supplier\Application\UseCase;

use Carbon\CarbonInterface;
use Module\Shared\Enum\CurrencyEnum;
use Module\Supplier\Application\Response\PriceDto;
use Module\Supplier\Application\Response\ServicePriceDto;
use Module\Supplier\Domain\Supplier\Repository\SupplierRepositoryInterface;
use Module\Supplier\Infrastructure\Supplier\Models\AirportServicePrice as Model;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class GetAirportServicePrice implements UseCaseInterface
{
    public function __construct(
        private readonly SupplierRepositoryInterface $supplierRepository,
    ) {}

    public function execute(
        int $supplierId,
        int $serviceId,
        int $airportId,
        CurrencyEnum $netCurrency,
        CurrencyEnum $grossCurrency,
        CarbonInterface $date
    ): ?ServicePriceDto {
        $supplier = $this->supplierRepository->find($supplierId);
        if ($supplier === null) {
            throw new EntityNotFoundException('Supplier not found');
        }
        $price = Model::whereSupplierId($supplierId)
            ->whereServiceId($serviceId)
            ->whereAirportId($airportId)
            ->whereCurrencyId($netCurrency->id())
            ->whereDate($date)
            ->first();
        if ($price === null) {
            throw new EntityNotFoundException('Service Price not found');
        }

        return $this->buildDtoFromModel($price, $grossCurrency);
    }

    private function buildDtoFromModel(Model $servicePrice, CurrencyEnum $grossCurrency): ?ServicePriceDto
    {
        $grossPrice = collect($servicePrice->prices_gross)->first(
            fn(array $priceData) => $priceData['currency_id'] === $grossCurrency->id()
        );
        if ($grossPrice === null) {
            return null;
        }

        return new ServicePriceDto(
            netPrice: new PriceDto(
                amount: $servicePrice->price_net,
                currency: CurrencyEnum::fromId($servicePrice->currency_id)
            ),
            grossPrice: new PriceDto(
                amount: (float)$grossPrice['amount'],
                currency: $grossCurrency
            ),
        );
    }
}
