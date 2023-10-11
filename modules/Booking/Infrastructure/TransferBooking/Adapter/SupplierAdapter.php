<?php

declare(strict_types=1);

namespace Module\Booking\Infrastructure\TransferBooking\Adapter;

use Carbon\CarbonInterface;
use Module\Booking\Deprecated\TransferBooking\Adapter\SupplierAdapterInterface;
use Module\Shared\Enum\CurrencyEnum;
use Module\Supplier\Application\Response\CancelConditionsDto;
use Module\Supplier\Application\Response\ServiceContractDto;
use Module\Supplier\Application\Response\ServicePriceDto;
use Module\Supplier\Application\Response\SupplierDto;
use Module\Supplier\Application\UseCase\Find;
use Module\Supplier\Application\UseCase\FindTransferServiceContract;
use Module\Supplier\Application\UseCase\GetTransferCancelConditions;
use Module\Supplier\Application\UseCase\GetTransferServicePrice;

class SupplierAdapter implements SupplierAdapterInterface
{
    public function find(int $id): SupplierDto
    {
        return app(Find::class)->execute($id);
    }

    public function getTransferServicePrice(
        int $supplierId,
        int $serviceId,
        int $carId,
        CurrencyEnum $grossCurrency,
        CarbonInterface $date
    ): ?ServicePriceDto {
        return app(GetTransferServicePrice::class)->execute(
            $supplierId,
            $serviceId,
            $carId,
            $grossCurrency,
            $date
        );
    }

    public function findTransferServiceContract(int $serviceId): ?ServiceContractDto
    {
        return app(FindTransferServiceContract::class)->execute($serviceId);
    }

    public function getTransferCancelConditions(): CancelConditionsDto
    {
        return app(GetTransferCancelConditions::class)->execute();
    }
}
