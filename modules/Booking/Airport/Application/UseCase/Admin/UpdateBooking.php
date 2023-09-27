<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Application\UseCase\Admin;

use Module\Booking\Airport\Application\Factory\CancelConditionsFactory;
use Module\Booking\Airport\Application\Request\UpdateBookingDto;
use Module\Booking\Airport\Domain\Entity\Booking;
use Module\Booking\Airport\Domain\Repository\BookingRepositoryInterface;
use Module\Booking\Common\Domain\Service\BookingUpdater;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class UpdateBooking implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $repository,
        private readonly BookingUpdater $bookingUpdater,
        private readonly CancelConditionsFactory $cancelConditionsFactory,
    ) {}

    public function execute(UpdateBookingDto $request): void
    {
        /** @var Booking $booking */
        $booking = $this->repository->find($request->id);
        if (!$booking->date()->eq($request->date)) {
            //@todo должна ли быть такая возможность?
            $booking->setDate();
            $cancelConditions = $this->cancelConditionsFactory->build($request->date);
            $booking->setCancelConditions($cancelConditions);
        }

        if ($booking->note() !== $request->note) {
            $booking->setNote($request->note);
        }

        $this->bookingUpdater->store($booking);
    }
}