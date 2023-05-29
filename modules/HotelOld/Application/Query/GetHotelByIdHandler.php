<?php

namespace Module\HotelOld\Application\Query;

use Module\HotelOld\Application\Dto\Info\HotelDto;
use Module\HotelOld\Domain\Repository\HotelRepositoryInterface;
use Sdk\Module\Contracts\Bus\QueryHandlerInterface;
use Sdk\Module\Contracts\Bus\QueryInterface;

class GetHotelByIdHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly HotelRepositoryInterface $repository
    ) {}

    /**
     * @param GetHotelById $query
     * @return HotelDto
     */
    public function handle(QueryInterface|GetHotelById $query): ?HotelDto
    {
        $hotel = $this->repository->find($query->id);
        if ($hotel === null) {
            return null;
        }
        return HotelDto::from($hotel);
    }
}
