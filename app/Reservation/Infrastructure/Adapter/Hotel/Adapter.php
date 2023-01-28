<?php

namespace GTS\Reservation\Infrastructure\Adapter\Hotel;

use GTS\Hotel\Infrastructure\Facade\Search\FacadeInterface;

class Adapter implements AdapterInterface
{

    public function __construct(private readonly FacadeInterface $hotelFacade)
    {
    }

    public function findById(int $id)
    {
        $hotelDto = $this->hotelFacade->findById($id);

        return $hotelDto;
    }
}
