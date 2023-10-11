<?php

namespace Module\Booking\Deprecated\TransferBooking\Service\PriceCalculator\Support;

use Carbon\CarbonInterface;
use Module\Booking\Deprecated\TransferBooking\Adapter\SupplierAdapterInterface;
use Module\Booking\Deprecated\TransferBooking\Exception\NotFoundTransferServicePrice;
use Module\Booking\Deprecated\TransferBooking\Service\PriceCalculator\Model\ServicePrice;
use Module\Shared\Enum\CurrencyEnum;

class ServicePriceFetcher
{
    public function __construct(
        private readonly SupplierAdapterInterface $supplierAdapter,
    ) {}

    public function fetch(
        int $supplierId,
        int $serviceId,
        int $carId,
        CarbonInterface $date,
        CurrencyEnum $orderCurrency,
    ): ServicePrice {
        $price = $this->supplierAdapter->getTransferServicePrice(
            $supplierId,
            $serviceId,
            $carId,
            $orderCurrency,
            $date
        );
        if ($price === null) {
            throw new NotFoundTransferServicePrice('Service price not found.');
        }

        return new ServicePrice(
            netPrice: $price->netPrice->amount,
            grossPrice: $price->grossPrice->amount,
        );
    }
}
