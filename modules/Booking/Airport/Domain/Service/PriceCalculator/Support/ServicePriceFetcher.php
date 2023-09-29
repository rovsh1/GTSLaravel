<?php

namespace Module\Booking\Airport\Domain\Service\PriceCalculator\Support;

use Carbon\CarbonInterface;
use Module\Booking\Airport\Domain\Adapter\SupplierAdapterInterface;
use Module\Booking\Airport\Domain\Exception\NotFoundAirportServicePrice;
use Module\Booking\Airport\Domain\Service\PriceCalculator\Model\ServicePrice;
use Module\Shared\Enum\CurrencyEnum;

class ServicePriceFetcher
{
    public function __construct(
        private readonly SupplierAdapterInterface $supplierAdapter,
    ) {}

    public function fetch(
        int $supplierId,
        int $serviceId,
        int $airportId,
        CarbonInterface $date,
        CurrencyEnum $orderCurrency,
        CurrencyEnum $supplierCurrency,
    ): ServicePrice {
        $price = $this->supplierAdapter->getAirportServicePrice(
            $supplierId,
            $serviceId,
            $airportId,
            $supplierCurrency,
            $orderCurrency,
            $date
        );
        if ($price === null) {
            throw new NotFoundAirportServicePrice('Service price not found.');
        }

        return new ServicePrice(
            netPrice: $price->netPrice->amount,
            grossPrice: $price->grossPrice->amount,
        );
    }
}
