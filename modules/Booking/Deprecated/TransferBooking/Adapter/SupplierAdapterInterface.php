<?php

declare(strict_types=1);

namespace Module\Booking\Deprecated\TransferBooking\Adapter;

use Carbon\CarbonInterface;
use Module\Shared\Enum\CurrencyEnum;
use Module\Supplier\Application\Response\CancelConditionsDto;
use Module\Supplier\Application\Response\ServiceContractDto;
use Module\Supplier\Application\Response\ServicePriceDto;
use Module\Supplier\Application\Response\SupplierDto;

interface SupplierAdapterInterface
{
    public function find(int $id): SupplierDto;

    public function getTransferServicePrice(
        int $supplierId,
        int $serviceId,
        int $carId,
        CurrencyEnum $grossCurrency,
        CarbonInterface $date
    ): ?ServicePriceDto;

    public function findTransferServiceContract(int $serviceId): ?ServiceContractDto;

    public function getTransferCancelConditions(): CancelConditionsDto;
}
