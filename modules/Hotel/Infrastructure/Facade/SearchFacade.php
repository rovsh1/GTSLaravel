<?php

namespace Module\Hotel\Infrastructure\Facade;

use Module\Hotel\Domain\Repository\HotelRepositoryInterface;

class SearchFacade implements SearchFacadeInterface
{
    public function __construct(
        private readonly HotelRepositoryInterface $repository
    ) {}
}
