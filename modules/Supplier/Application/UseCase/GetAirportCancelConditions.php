<?php

declare(strict_types=1);

namespace Module\Supplier\Application\UseCase;

use Carbon\CarbonInterface;
use Module\Shared\Enum\CurrencyEnum;
use Module\Supplier\Application\Response\CancelConditionsDto;
use Module\Supplier\Application\Response\PriceDto;
use Module\Supplier\Application\Response\ServicePriceDto;
use Module\Supplier\Domain\Supplier\Repository\CancelConditionsRepositoryInterface;
use Module\Supplier\Domain\Supplier\Repository\SupplierRepositoryInterface;
use Module\Supplier\Infrastructure\Models\AirportServicePrice as Model;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class GetAirportCancelConditions implements UseCaseInterface
{
    public function __construct(
        private readonly CancelConditionsRepositoryInterface $cancelConditionsRepository
    ) {}

    public function execute(): CancelConditionsDto
    {
        $cancelConditions = $this->cancelConditionsRepository->get();

        return CancelConditionsDto::fromDomain($cancelConditions);
    }
}
