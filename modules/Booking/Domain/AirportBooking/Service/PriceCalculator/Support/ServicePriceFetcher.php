<?php

namespace Module\Booking\Domain\AirportBooking\Service\PriceCalculator\Support;

use Carbon\CarbonInterface;
use Module\Booking\Domain\AirportBooking\Adapter\SupplierAdapterInterface;
use Module\Booking\Domain\AirportBooking\Exception\NotFoundAirportServicePrice;
use Module\Booking\Domain\AirportBooking\Service\PriceCalculator\Model\ServicePrice;
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
    ): ServicePrice {
        $price = $this->supplierAdapter->getAirportServicePrice(
            $supplierId,
            $serviceId,
            $airportId,
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
