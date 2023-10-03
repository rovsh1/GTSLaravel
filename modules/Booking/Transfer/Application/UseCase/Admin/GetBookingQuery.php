<?php

declare(strict_types=1);

namespace Module\Booking\Transfer\Application\UseCase\Admin;

use Illuminate\Database\Eloquent\Builder;
use Module\Booking\Transfer\Domain\Booking\Repository\BookingRepositoryInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetBookingQuery implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $repository
    ) {}

    public function execute(): Builder
    {
        return $this->repository->query();
    }
}