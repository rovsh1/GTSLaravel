<?php

declare(strict_types=1);

namespace Module\Booking\Application\AirportBooking\UseCase\Admin;

use Module\Booking\Application\AirportBooking\Factory\CancelConditionsFactory;
use Module\Booking\Application\AirportBooking\Request\UpdateBookingDto;
use Module\Booking\Domain\AirportBooking\AirportBooking;
use Module\Booking\Domain\AirportBooking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\AirportBooking\ValueObject\Details\AdditionalInfo;
use Module\Booking\Domain\Shared\Service\BookingUpdater;
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
        /** @var AirportBooking $booking */
        $booking = $this->repository->find($request->id);
        if (!$booking->date()->eq($request->date)) {
            $booking->setDate($request->date->toImmutable());
            $cancelConditions = $this->cancelConditionsFactory->build($request->date);
            $booking->setCancelConditions($cancelConditions);
        }

        if ($booking->additionalInfo()->flightNumber() !== $request->flightNumber) {
            $booking->setAdditionalInfo(
                new AdditionalInfo($request->flightNumber)
            );
        }

        if ($booking->note() !== $request->note) {
            $booking->setNote($request->note);
        }

        $this->bookingUpdater->store($booking);
    }
}
