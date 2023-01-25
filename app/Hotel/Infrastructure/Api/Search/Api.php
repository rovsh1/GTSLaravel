<?php

namespace GTS\Hotel\Infrastructure\Api\Search;

use GTS\Hotel\Domain\Repository\HotelRepositoryInterface;

class Api implements ApiInterface
{

    public function __construct(
        private readonly HotelRepositoryInterface $repository
    ) {
    }

    public function findById(int $id)
    {
        $hotel = $this->repository->findById($id);

        return $hotel; //TODO convert to DTO
    }
}
