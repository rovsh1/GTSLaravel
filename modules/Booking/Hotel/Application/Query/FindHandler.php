<?php

namespace Module\Booking\Hotel\Application\Query;

use Custom\Framework\Contracts\Bus\QueryHandlerInterface;
use Custom\Framework\Contracts\Bus\QueryInterface;
use Module\Booking\Hotel\Application\Dto\ReservationDto;
use Module\Booking\Hotel\Domain\Repository\BookingRepositoryInterface;

class FindHandler implements QueryHandlerInterface
{
    public function __construct(private readonly BookingRepositoryInterface $repository) {}

    public function handle(QueryInterface|Find $query): mixed
    {
        $model = $this->repository->find($query->id);
        if (!$model) {
            return null;
        }
        return ReservationDto::fromDomain($model);
    }
}
