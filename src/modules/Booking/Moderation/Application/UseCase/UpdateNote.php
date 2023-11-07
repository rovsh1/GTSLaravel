<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase;

use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Shared\Service\BookingUpdater;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class UpdateNote implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $repository,
        private readonly BookingUpdater $bookingUpdater,
    ) {}

    public function execute(int $id, ?string $note): void
    {
        $booking = $this->repository->find(new BookingId($id));
        $booking->setNote($note);
        $this->bookingUpdater->store($booking);
    }
}
