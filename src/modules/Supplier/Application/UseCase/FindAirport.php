<?php

declare(strict_types=1);

namespace Module\Supplier\Application\UseCase;

use Module\Supplier\Application\Dto\AirportDto;
use Module\Supplier\Infrastructure\Models\Airport;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class FindAirport implements UseCaseInterface
{
    public function execute(int $carId): ?AirportDto
    {
        $airport = Airport::find($carId);
        if ($airport === null) {
            return null;
        }

        return new AirportDto(
            $airport->id,
            $airport->name
        );
    }
}
