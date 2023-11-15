<?php

declare(strict_types=1);

namespace Module\Supplier\Moderation\Application\UseCase;

use Illuminate\Database\Eloquent\Collection;
use Module\Supplier\Moderation\Application\Factory\CarDtoFactory;
use Module\Supplier\Moderation\Infrastructure\Models\Car;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetCars implements UseCaseInterface
{
    public function __construct(
        private readonly CarDtoFactory $factory
    ) {}

    public function execute(int $supplierId): array
    {
        $cars = Car::whereSupplierId($supplierId)->get();

        return $this->buildDTOs($cars);
    }

    private function buildDTOs(Collection $cars): array
    {
        return $cars->map(fn(Car $car) => $this->factory->build($car))->all();
    }
}
