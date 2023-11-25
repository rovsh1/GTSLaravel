<?php

declare(strict_types=1);

namespace Module\Supplier\Moderation\Application\UseCase\CancelConditions;

use Module\Supplier\Moderation\Application\Response\CancelConditionsDto;
use Module\Supplier\Moderation\Domain\Supplier\Repository\CarCancelConditionsRepositoryInterface;
use Module\Supplier\Moderation\Domain\Supplier\Repository\SeasonRepositoryInterface;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\CarId;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\SeasonId;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\ServiceId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetCarCancelConditions implements UseCaseInterface
{
    public function __construct(
        private readonly CarCancelConditionsRepositoryInterface $cancelConditionsRepository,
        private readonly SeasonRepositoryInterface $seasonRepository,
    ) {}

    public function execute(
        int $serviceId,
        int $carId,
        ?\DateTimeInterface $date = null,
        ?int $seasonId = null
    ): ?CancelConditionsDto {
        $serviceId = new ServiceId($serviceId);
        if ($seasonId === null && $date !== null) {
            $seasonId = $this->seasonRepository->findActiveSeasonId($serviceId, $date);
        } elseif ($seasonId !== null) {
            $seasonId = new SeasonId($seasonId);
        }
        if ($seasonId === null) {
            throw new \RuntimeException('Not found supplier seasons for service');
        }
        $cancelConditions = $this->cancelConditionsRepository->get(
            serviceId: $serviceId,
            carId: new CarId($carId),
            seasonId: $seasonId,
        );
        if ($cancelConditions === null) {
            return null;
        }

        return CancelConditionsDto::fromDomain($cancelConditions);
    }
}
