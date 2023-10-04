<?php

namespace Module\Booking\Domain\TransferBooking\Service\PriceCalculator\Support;

use Carbon\CarbonInterface;
use Module\Booking\Domain\TransferBooking\Adapter\SupplierAdapterInterface;
use Module\Booking\Domain\TransferBooking\Exception\NotFoundTransferServicePrice;
use Module\Booking\Domain\TransferBooking\Service\PriceCalculator\Model\ServicePrice;
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
        CurrencyEnum $supplierCurrency,
    ): ServicePrice {
        $price = $this->supplierAdapter->getTransferServicePrice(
            $supplierId,
            $serviceId,
            $carId,
            $supplierCurrency,
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
