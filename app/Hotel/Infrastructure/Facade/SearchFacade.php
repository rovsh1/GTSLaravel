<?php

namespace GTS\Hotel\Infrastructure\Facade;

use GTS\Hotel\Domain\Repository\HotelRepositoryInterface;

class SearchFacade implements SearchFacadeInterface
{
    public function __construct(
        private readonly HotelRepositoryInterface $repository
    ) {}
}
