<?php

namespace Module\HotelOld\Application\Query;

use Custom\Framework\Contracts\Bus\QueryHandlerInterface;
use Custom\Framework\Contracts\Bus\QueryInterface;
use Module\HotelOld\Application\Dto\Info\RoomDto;
use Module\HotelOld\Domain\Repository\RoomRepositoryInterface;

class GetRoomsWithPriceRatesByHotelIdHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly RoomRepositoryInterface $repository
    ) {}

    /**
     * @param GetRoomsWithPriceRatesByHotelId $query
     * @return RoomDto[]
     */
    public function handle(QueryInterface|GetRoomsWithPriceRatesByHotelId $query): array
    {
        $rooms = $this->repository->getRoomsWithPriceRatesByHotelId($query->hotelId);

        return RoomDto::collection($rooms)->all();
    }
}
