<?php

namespace Module\Booking\Hotel\Infrastructure\Adapter\Hotel;

use Module\Hotel\Infrastructure\Facade\SearchFacadeInterface;

class Adapter implements AdapterInterface
{

    public function __construct(private readonly SearchFacadeInterface $hotelFacade)
    {
    }

    public function findById(int $id)
    {
        $hotelDto = $this->hotelFacade->findById($id);

        return $hotelDto;
    }
}
