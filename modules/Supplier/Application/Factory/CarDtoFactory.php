<?php

declare(strict_types=1);

namespace Module\Supplier\Application\Factory;

use Module\Supplier\Application\Dto\CarDto;
use Module\Supplier\Infrastructure\Models\Car;

class CarDtoFactory
{
    public function build(Car $car): CarDto
    {
        return new CarDto(
            id: $car->id,
            typeId: $car->type_id,
            mark: $car->mark,
            model: $car->model,
            passengersNumber: $car->passengers_number,
            bagsNumber: $car->bags_number,
        );
    }
}
