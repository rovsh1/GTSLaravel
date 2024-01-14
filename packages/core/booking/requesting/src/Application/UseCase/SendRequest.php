<?php

declare(strict_types=1);

namespace Pkg\Booking\Requesting\Application\UseCase;

use Module\Booking\Shared\Domain\Booking\Service\BookingUnitOfWorkInterface;
use Pkg\Booking\Requesting\Domain\Factory\RequestFactory;
use Pkg\Booking\Requesting\Domain\Service\RequestingRules;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class SendRequest implements UseCaseInterface
{
    public function __construct(
        private readonly BookingUnitOfWorkInterface $bookingUnitOfWork,
        private readonly RequestFactory $requestFactory,
        private readonly RequestingRules $requestRules,
    ) {}

    public function execute(int $id): void
    {
        $booking = $this->bookingUnitOfWork->findOrFail(new BookingId($id));

        $this->requestRules->booking($booking);

        $this->bookingUnitOfWork->commiting(function () use ($booking) {
            $this->requestFactory->generate($booking, $this->requestRules->getRequestType());
        });
        $this->bookingUnitOfWork->touch($booking->id());
        $this->bookingUnitOfWork->commit();
    }
}
