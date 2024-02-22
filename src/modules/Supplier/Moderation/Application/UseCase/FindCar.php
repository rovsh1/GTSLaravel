<?php

declare(strict_types=1);

namespace Module\Supplier\Moderation\Application\UseCase;

use Module\Supplier\Moderation\Application\Dto\CarDto;
use Module\Supplier\Moderation\Application\Factory\CarDtoFactory;
use Module\Supplier\Moderation\Infrastructure\Models\Car;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class FindCar implements UseCaseInterface
{
    public function __construct(
        private readonly CarDtoFactory $factory
    ) {}

    public function execute(int $carId): ?CarDto
    {
        $car = Car::find($carId);
        if ($car === null) {
            return null;
        }

        return $this->factory->build($car);
    }
}
