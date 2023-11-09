<?php

declare(strict_types=1);

namespace Module\Administrator\Application\UseCase;

use Module\Administrator\Domain\Repository\BookingAdministratorRepositoryInterface;
use Module\Administrator\Domain\ValueObject\AdministratorId;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class SetBookingAdministrator implements UseCaseInterface
{
    public function __construct(
        private readonly BookingAdministratorRepositoryInterface $bookingAdministratorRepository
    ) {}

    public function execute(int $orderId, int $administratorId): void
    {
        $this->bookingAdministratorRepository->set(new BookingId($orderId), new AdministratorId($administratorId));
    }
}
