<?php

namespace GTS\Hotel\Infrastructure\Facade\Search;

use GTS\Hotel\Domain\Repository\HotelRepositoryInterface;

class Facade implements FacadeInterface
{
    public function __construct(
        private readonly HotelRepositoryInterface $repository
    ) {}
}
