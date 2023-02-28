<?php

namespace Module\Reservation\HotelReservation\Application\Query;

use Custom\Framework\Contracts\Bus\QueryHandlerInterface;
use Custom\Framework\Contracts\Bus\QueryInterface;
use Module\Reservation\HotelReservation\Application\Dto\ReservationDto;
use Module\Reservation\HotelReservation\Domain\Repository\ReservationExtendedRepositoryInterface;

class FindHandler implements QueryHandlerInterface
{
    public function __construct(private readonly ReservationExtendedRepositoryInterface $repository) {}

    public function handle(QueryInterface|Find $query): ?ReservationDto
    {
        $model = $this->repository->find($query->id);
        if (!$model) {
            return null;
        }
        return ReservationDto::fromDomain($model);
    }
}
