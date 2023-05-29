<?php

namespace Module\Booking\Hotel\Application\Query;

use Module\Booking\Hotel\Application\Dto\ReservationDto;
use Module\Booking\Hotel\Domain\Repository\BookingRepositoryInterface;
use Sdk\Module\Contracts\Bus\QueryHandlerInterface;
use Sdk\Module\Contracts\Bus\QueryInterface;

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
