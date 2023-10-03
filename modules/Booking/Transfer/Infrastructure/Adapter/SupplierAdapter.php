<?php

declare(strict_types=1);

namespace Module\Booking\Transfer\Infrastructure\Adapter;

use Carbon\CarbonInterface;
use Module\Booking\Transfer\Domain\Booking\Adapter\SupplierAdapterInterface;
use Module\Shared\Enum\CurrencyEnum;
use Module\Supplier\Application\Response\CancelConditionsDto;
use Module\Supplier\Application\Response\ServiceContractDto;
use Module\Supplier\Application\Response\ServicePriceDto;
use Module\Supplier\Application\Response\SupplierDto;
use Module\Supplier\Application\UseCase\Find;
use Module\Supplier\Application\UseCase\GetTransferCancelConditions;

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
        CurrencyEnum $netCurrency,
        CurrencyEnum $grossCurrency,
        CarbonInterface $date
    ): ?ServicePriceDto {
        // TODO: Implement getTransferServicePrice() method.
    }

    public function findTransferServiceContract(int $serviceId): ?ServiceContractDto
    {
        // TODO: Implement findTransferServiceContract() method.
    }

    public function getTransferCancelConditions(): CancelConditionsDto
    {
        return app(GetTransferCancelConditions::class)->execute();
    }
}
