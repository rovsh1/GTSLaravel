<?php

declare(strict_types=1);

namespace Module\Supplier\Application\UseCase;

use Illuminate\Database\Eloquent\Collection;
use Module\Supplier\Application\Dto\CarDto;
use Module\Supplier\Infrastructure\Models\Car;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetCars implements UseCaseInterface
{
    public function execute(int $supplierId): array
    {
        $cars = Car::whereSupplierId($supplierId)->get();

        return $this->buildDTOs($cars);
    }

    private function buildDTOs(Collection $cars): array
    {
        return $cars->map(fn(Car $car) => new CarDto(
            id: $car->id,
            typeId: $car->type_id,
            mark: $car->mark,
            model: $car->model,
            passengersNumber: $car->passengers_number,
            bagsNumber: $car->bags_number,
        ))->all();
    }
}
